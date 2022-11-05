<?php
declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use App\Tests\Resource\Fixture\UserFixture;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;

class SecurityControllerCase extends AbstractControllerCase
{
    private AbstractDatabaseTool $databaseTool;

    public function setUp(): void
    {
        parent::setUp();
        $this->databaseTool = self::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function testLoginSuccessfully(): void
    {
        // Arrange
        $this->client->followRedirects();

        $executor = $this->databaseTool->loadFixtures([UserFixture::class]);
        $user = $executor->getReferenceRepository()->getReference(UserFixture::REFERENCE);

        // Act
        $crawler = $this->client->request('GET', '/login');

        $btnLogin = $crawler->selectButton('Login');
        $formLogin = $btnLogin->form();

        $formLogin['email'] = $user->getEmail();
        $formLogin['password'] = $user->getPassword();

        $this->client->submit($formLogin);

        // Assert
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('title', 'Movie list');
    }
}
