<?php
declare(strict_types=1);

namespace App\Tests\functional;

use App\Entity\User;
use App\Tests\FunctionalTester;
use Codeception\Util\HttpCode;
use Faker\Factory;
use Faker\Generator;

class RegistrationCest
{
    private Generator $faker;

    public function _before(FunctionalTester $I): void
    {
        $this->faker = Factory::create();
    }

    public function tryRegister(FunctionalTester $I): void
    {
        $email = $this->faker->email;
        $password = $this->faker->password;

        $I->amOnPage('/register');
        $I->fillField('registration_form[email]', $email);
        $I->fillField('registration_form[plainPassword][first]', $password);
        $I->fillField('registration_form[plainPassword][second]', $password);
        $I->checkOption('registration_form[agreeTerms]');
        $I->click('Register');

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeInTitle('Movie list');
        $I->seeInRepository(User::class, [
            'email' => $email,
        ]);
    }
}
