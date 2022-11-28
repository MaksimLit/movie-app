<?php
declare(strict_types=1);

namespace App\Tests\functional;

use App\Entity\Movie;
use App\Entity\User;
use App\Tests\FunctionalTester;
use App\Tests\Resource\Fixture\MovieFixtures;
use App\Tests\Resource\Fixture\UserFixtures;
use Codeception\Util\HttpCode;
use Faker\Factory;
use Faker\Generator;

class MovieCest
{
    private Generator $faker;

    public function _before(FunctionalTester $I): void
    {
        $this->faker = Factory::create();
    }

    public function tryShowSearchResultIfMovieIsFound(FunctionalTester $I): void
    {
        $I->loadFixtures(UserFixtures::class, false);
        $user = $I->grabEntityFromRepository(User::class);
        $I->amLoggedInAs($user);

        $I->amOnPage('/movie/search');
        $I->fillField('name', 'Остров проклятых');
        $I->click('search');

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->see('Остров проклятых', 'h5');
    }

    public function tryShowSearchResultIfMovieIsNotFound(FunctionalTester $I): void
    {
        $I->loadFixtures(UserFixtures::class, false);
        $user = $I->grabEntityFromRepository(User::class);
        $I->amLoggedInAs($user);

        $I->amOnPage('/movie/search');
        $I->fillField('name', 'Название несуществующего фильма');
        $I->click('search');

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeInSource('Nothing found');
    }

    public function tryShowMovieListIfListIsNotEmpty(FunctionalTester $I): void
    {
        $I->loadFixtures([UserFixtures::class, MovieFixtures::class], false);
        $user = $I->grabEntityFromRepository(User::class);
        $movie = $I->grabEntityFromRepository(Movie::class);

        $I->amLoggedInAs($user);

        $I->amOnPage('/movie/list');

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeInTitle('Movie list');
        $I->see($movie->getName(), 'h5.card-title');
        $I->see((string) $movie->getRatingKp(), 'small.ratingKp');
        $I->see((string) $movie->getRatingImdb(), 'small.ratingImdb');
        $I->see((string) $movie->getYear(), 'small.year');
    }

    public function tryShowMovieListIfListIsEmpty(FunctionalTester $I): void
    {
        $I->loadFixtures([UserFixtures::class], false);
        $user = $I->grabEntityFromRepository(User::class);

        $I->amLoggedInAs($user);

        $I->amOnPage('/movie/list');

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeInSource('Your movie list is empty');
    }

    public function tryDeleteMovieFromList(FunctionalTester $I): void
    {
        $I->loadFixtures([UserFixtures::class, MovieFixtures::class], false);
        $user = $I->grabEntityFromRepository(User::class);

        $movie = $I->grabEntityFromRepository(Movie::class);
        $movieId = $movie->getId();

        $I->amLoggedInAs($user);

        $I->amOnPage('/movie/list');
        $I->click('Delete');

        $I->dontSeeInRepository(Movie::class, ['id' => $movieId]);
    }

    public function tryAddMovieToList(FunctionalTester $I): void
    {
        $I->loadFixtures(UserFixtures::class, false);
        $user = $I->grabEntityFromRepository(User::class);
        $I->amLoggedInAs($user);

        $movieData = [
            'kpId' => $this->faker->randomNumber(6),
            'name' => $this->faker->title,
            'year' => $this->faker->year,
            'desc' => $this->faker->realText(40),
            'ratingImdb' => $this->faker->randomFloat(2),
            'ratingkp' => $this->faker->randomFloat(2),
            'posterUrl' => $this->faker->imageUrl(185, 275),
        ];

        $I->sendAjaxPostRequest('/movie/add', $movieData);

        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeInRepository(Movie::class, [
            'kpId' => $movieData['kpId'],
        ]);
    }
}
