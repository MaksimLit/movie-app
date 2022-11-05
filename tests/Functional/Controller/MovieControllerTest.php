<?php
declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use App\Entity\Movie;
use App\Tests\Resource\Fixture\UserFixture;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;

class MovieControllerCase extends AbstractControllerCase
{
    private AbstractDatabaseTool $databaseTool;

    public function setUp(): void
    {
        parent::setUp();
        $this->databaseTool = self::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function testShowSearchResultIfMovieFound(): void
    {
        // Arrange
        $this->client->followRedirects();

        $executor = $this->databaseTool->loadFixtures([UserFixture::class]);
        $user = $executor->getReferenceRepository()->getReference(UserFixture::REFERENCE);
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

    public function testAddMovieToList(): void
    {
        // Arrange
        $executor = $this->databaseTool->loadFixtures([UserFixture::class]);
        $user = $executor->getReferenceRepository()->getReference(UserFixture::REFERENCE);
        $this->client->loginUser($user);

        $movieData = [
            'kpId' => '397667',
            'name' => 'Остров проклятых',
            'year' => '2009',
            'desc' => 'Два американских судебных пристава отправляются на один из островов в штате Массачусетс, чтобы расследовать исчезновение пациентки клиники для умалишенных преступников. При проведении расследования им придется столкнуться с паутиной лжи, обрушившимся ураганом и смертельным бунтом обитателей клиники.',
            'ratingImdb' => '8.2',
            'ratingkp' => '8.505',
            'posterUrl' => 'https://st.kp.yandex.net/images/film_big/397667.jpg',
        ];

        // Act
        $this->client->xmlHttpRequest('POST', '/movie/add', $movieData);

        // Assert
        $this->assertResponseIsSuccessful();

        $jsonResult = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('Movie added successfully', $jsonResult['message']);

        $movie = $this->em->getRepository(Movie::class)->findOneBy(['kpId' => 397667]);
        $this->assertEquals('397667', $movie->getKpId());
    }
}
