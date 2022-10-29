<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class PreviewController extends AbstractController
{
    #[Route('/')]
    public function index(): RedirectResponse
    {
        return $this->redirectToRoute('app_movie_list');
    }
}
