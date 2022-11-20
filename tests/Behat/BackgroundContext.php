<?php
declare(strict_types=1);

namespace App\Tests\Behat;

use App\Tests\Resource\Fixture\UserFixtures;
use Behat\Behat\Context\Context;
use Behat\Mink\Exception\ElementNotFoundException;
use Behat\Mink\Session;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;

class BackgroundContext implements Context
{
    private Session $session;

    private AbstractDatabaseTool $databaseTool;

    public function __construct(DatabaseToolCollection $databaseTool, Session $session)
    {
        $this->databaseTool = $databaseTool->get();
        $this->session = $session;
    }

    /**
     * @When /^I go to the page as an unauthorized user$/
     */
    public function iGoToThePageAsAnUnauthorizedUser()
    {
        $this->databaseTool->loadFixtures([UserFixtures::class]);
    }

    /**
     * @Given /^I am logged in as a user$/
     * @throws ElementNotFoundException
     */
    public function iAmLoggedInAsAUser()
    {
        $this->databaseTool->loadFixtures([UserFixtures::class]);

        $this->session->visit('/login');
        $page = $this->session->getPage();
        $page->fillField('email', 'test@mail.com');
        $page->fillField('password', '123456');
        $page->pressButton('Login');
    }
}
