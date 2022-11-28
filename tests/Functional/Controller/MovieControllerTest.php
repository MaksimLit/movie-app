<?php
declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use App\Entity\Movie;
use App\Tests\AbstractControllerTest;
use App\Tests\Resource\Fixture\MovieFixtures;
use App\Tests\Resource\Fixture\UserFixtures;
use Faker\Factory;
use Faker\Generator;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;

class MovieControllerTest extends AbstractControllerTest
{
    private AbstractDatabaseTool $databaseTool;
    private Generator $faker;

    public function setUp(): void
    {
        parent::setUp();
        $this->databaseTool = self::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->faker = Factory::create();
    }

    public function testShowSearchResultIfMovieFound(): void
    {
        // Arrange
        $this->client->followRedirects();

        $executor = $this->databaseTool->loadFixtures([UserFixtures::class]);
        $user = $executor->getReferenceRepository()->getReference(UserFixtures::REFERENCE);
        $this->client->loginUser($user);

        // Act
        $crawler = $this->client->request('GET', '/movie/search');

        $btnSearch = $crawler->selectButton('search');
        $formSearch = $btnSearch->form();
        $formSearch['name'] = 'Остров проклятых';

        $this->client->submit($formSearch);

        // Assert
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h5', 'Остров проклятых');
    }

    public function testShowSearchResultIfMovieNotFound(): void
    {
        // Arrange
        $this->client->followRedirects();

        $executor = $this->databaseTool->loadFixtures([UserFixtures::class]);
        $user = $executor->getReferenceRepository()->getReference(UserFixtures::REFERENCE);
        $this->client->loginUser($user);

        // Act
        $crawler = $this->client->request('GET', '/movie/search');

        $btnSearch = $crawler->selectButton('search');
        $formSearch = $btnSearch->form();
        $formSearch['name'] = 'Название несуществующего фильма';

        $this->client->submit($formSearch);

        // Assert
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('div.flash-notice', 'Nothing found');
    }

    public function testShowMovieListIfListIsEmpty(): void
    {
        // Arrange
        $this->client->followRedirects();

        $executor = $this->databaseTool->loadFixtures([UserFixtures::class]);
        $user = $executor->getReferenceRepository()->getReference(UserFixtures::REFERENCE);

        $this->client->loginUser($user);

        // Act
        $this->client->request('GET', '/movie/list');

        // Assert
        $this->assertResponseIsSuccessful();
        $this->assertPageTitleSame('Movie list');
        $this->assertSelectorTextContains('h3', 'Your movie list is empty');
    }

    public function testDeleteMovieFromList():void
    {
        // Arrange
        $this->client->followRedirects();

        $executor = $this->databaseTool->loadFixtures([UserFixtures::class, MovieFixtures::class]);
        $user = $executor->getReferenceRepository()->getReference(UserFixtures::REFERENCE);
        $movie = $executor->getReferenceRepository()->getReference(MovieFixtures::REFERENCE);

        $this->client->loginUser($user);

        // Act
        $crawler = $this->client->request('GET', '/movie/list');

        $deleteBtn = $crawler->selectButton('Delete');
        $deleteForm = $deleteBtn->form();

        $this->client->submit($deleteForm);

        // Assert
        $this->assertResponseIsSuccessful();
        $this->assertPageTitleSame('Movie list');
        $this->assertSelectorTextNotContains('body', $movie->getName());
    }

    public function testAddMovieToList(): void
    {
        // Arrange
        $executor = $this->databaseTool->loadFixtures([UserFixtures::class]);
        $user = $executor->getReferenceRepository()->getReference(UserFixtures::REFERENCE);
        $this->client->loginUser($user);

        $movieData = [
            'kpId' => $this->faker->randomNumber(6),
            'name' => $this->faker->title,
            'year' => $this->faker->year,
            'desc' => $this->faker->realText(40),
            'ratingImdb' => $this->faker->randomFloat(2),
            'ratingkp' => $this->faker->randomFloat(2),
            'posterUrl' => $this->faker->imageUrl(185, 275),
        ];

        // Act
        $this->client->xmlHttpRequest('POST', '/movie/add', $movieData);

        // Assert
        $this->assertResponseIsSuccessful();

        $jsonResult = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('Movie added successfully', $jsonResult['message']);

        $movie = $this->em->getRepository(Movie::class)->findOneBy(['kpId' => $movieData['kpId']]);
        $this->assertEquals($movieData['kpId'], $movie->getKpId());
    }
}
