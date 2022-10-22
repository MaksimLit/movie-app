<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use App\Service\KinopoiskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/movie', 'app_movie_')]
class MovieController extends AbstractController
{
    #[Route('/search', name: 'search', methods: ['GET'])]
    public function search(Request $request, KinopoiskService $kinopoiskService, MovieRepository $movieRepository):Response
    {
        $movieName = $request->query->get('name');

        $movies = [];
        if ($movieName) {
            $user = $this->getUser();
            $movieListFromKp = $kinopoiskService->searchByName($movieName);
            $movieListFromDb = $movieRepository->getMoviesByUser($user);

            $items = [];
            foreach ($movieListFromKp as $key => $movieFromKp) {
                $items[] = $movieFromKp->setIsIncluded(false);
                foreach ($movieListFromDb as $movieFromDb) {
                    if ($movieFromKp->getKpId() === $movieFromDb->getKpId()) {
                        $items[$key] = $movieFromKp->setIsIncluded(true);
                    }
                }
            }

            $movies = $items;
        }

        return $this->render('movie/search.html.twig', [
            'movies' => $movies,
        ]);
    }

    #[Route('/add', name: 'add', methods: ['POST'])]
    public function add(Request $request, MovieRepository $movieRepository): JsonResponse
    {
        $kpId = $request->request->get('kpId');
        $name = $request->request->get('name');
        $year = $request->request->get('year');
        $desc = $request->request->get('desc');
        $ratingImdb = $request->request->get('ratingImdb');
        $ratingKp = $request->request->get('ratingKp');
        $posterUrl = $request->request->get('posterUrl');

        $user = $this->getUser();

        $movie = (new Movie())
                ->setKpId((int) $kpId)
                ->setName($name)
                ->setYear((int) $year)
                ->setDescription($desc)
                ->setRatingImdb((float) $ratingImdb)
                ->setRatingKp((float) $ratingKp)
                ->setPosterUrl($posterUrl)
                ->addViewer($user);

        $movieRepository->save($movie, true);

        return new JsonResponse(['message' => 'Movie added successfully'],201);
    }

    #[Route('/list', name: 'list', methods: ['GET'])]
    public function showList(MovieRepository $movieRepository): Response
    {
        $user   = $this->getUser();
        $movies = $movieRepository->getMoviesByUser($user);

        return $this->render('movie/list.html.twig', [
            'movies' => $movies,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Movie $movie, MovieRepository $movieRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$movie->getId(), $request->request->get('_token'))) {
            $movieRepository->remove($movie, true);
        }

        return $this->redirectToRoute('app_movie_list', [], Response::HTTP_SEE_OTHER);
    }
}
