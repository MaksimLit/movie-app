<?php
declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use App\Entity\User;

class RegistrationControllerCase extends AbstractControllerCase
{
    private const EMAIL = 'test@mail.com';
    private const PASSWORD = 123456;

    public function testRegistrationSuccessfully(): void
    {
        // Arrange
        $this->client->followRedirects();

        // Act
        $crawler = $this->client->request('GET', '/register');

        $buttonCrawlerNode = $crawler->selectButton('Register');
        $form = $buttonCrawlerNode->form();

        $form['registration_form[email]'] = self::EMAIL;
        $form['registration_form[plainPassword][first]'] = self::PASSWORD;
        $form['registration_form[plainPassword][second]'] = self::PASSWORD;
        $form['registration_form[agreeTerms]']->tick();

        $this->client->submit($form);

        // Assert
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('title', 'Movie list');

        $existingUser = $this->em->getRepository(User::class)->findOneBy(['email' => self::EMAIL]);
        $this->assertEquals(self::EMAIL, $existingUser->getEmail());
    }
}
