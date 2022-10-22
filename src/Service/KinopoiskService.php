<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Movie;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class KinopoiskService
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function searchByName(string $name): array
    {
        $response = $this->client->request(
            'GET',
            'https://api.kinopoisk.dev/movie',
            [
                'query' => [
                    'token' => $_ENV['KINOPOISK_API_TOKEN'],
                    'field' => 'name',
                    'search' => $name,
                    'isStrict' => 'false',
                ],
            ],
        );

        $content = $response->toArray();

        $items = array_map(
            fn($doc) => (new Movie)
                ->setKpId($doc['id'] ?? '')
                ->setName($doc['name'] ?? '')
                ->setYear($doc['year'] ?? null)
                ->setDescription($doc['description'] ?? '')
                ->setRatingImdb($doc['rating']['imdb'] ?? null)
                ->setRatingKp($doc['rating']['kp'] ?? null)
                ->setPosterUrl($doc['poster']['url'] ?? '')
            , $content['docs'] ?? []
        );

        return $items;
    }
}
