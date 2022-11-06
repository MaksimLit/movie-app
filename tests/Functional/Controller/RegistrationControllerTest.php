<?php
declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use App\Entity\User;
use App\Tests\AbstractControllerTest;
use Faker\Factory;
use Faker\Generator;

class RegistrationControllerTest extends AbstractControllerTest
{
    private Generator $faker;

    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
    }

    public function testRegistrationSuccessfully(): void
    {
        // Arrange
        $this->client->followRedirects();
        $mail = $this->faker->email;
        $password = $this->faker->password;

        // Act
        $crawler = $this->client->request('GET', '/register');

        $buttonCrawlerNode = $crawler->selectButton('Register');
        $form = $buttonCrawlerNode->form();

        $form['registration_form[email]'] = $mail;
        $form['registration_form[plainPassword][first]'] = $password;
        $form['registration_form[plainPassword][second]'] = $password;
        $form['registration_form[agreeTerms]']->tick();

        $this->client->submit($form);

        // Assert
        $this->assertResponseIsSuccessful();
        $this->assertPageTitleSame('Movie list');

        $existingUser = $this->em->getRepository(User::class)->findOneBy(['email' => $mail]);
        $this->assertEquals($mail, $existingUser->getEmail());
    }
}
