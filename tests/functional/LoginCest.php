<?php
declare(strict_types=1);

namespace App\Tests\functional;

use App\Entity\User;
use App\Tests\FunctionalTester;
use App\Tests\Resource\Fixture\UserFixtures;
use Codeception\Util\HttpCode;

class LoginCest
{
    public function tryLoginIfCredentialIsCorrect(FunctionalTester $I): void
    {
        $I->loadFixtures(UserFixtures::class, false);
        $user = $I->grabEntityFromRepository(User::class);

        $I->amOnPage('/login');
        $I->fillField('email', $user->getEmail());
        $I->fillField('password', $user->getPassword());
        $I->click('Login');

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeInTitle('Movie list');
    }

    public function tryLoginIfCredentialIsNotCorrect(FunctionalTester $I): void
    {
        $I->loadFixtures(UserFixtures::class, false);
        $user = $I->grabEntityFromRepository(User::class);

        $I->amOnPage('/login');
        $I->fillField('email', $user->getEmail());
        $I->fillField('password', $user->getPassword().'p');
        $I->click('Login');

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeInSource('Invalid credentials.');
    }
}
