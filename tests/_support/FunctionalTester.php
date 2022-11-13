<?php
namespace App\Tests;

use App\Entity\User;
use App\Tests\Resource\Fixture\UserFixtures;
use Behat\Behat\Tester\Exception\PendingException;
use PHPUnit\Framework\IncompleteTestError;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class FunctionalTester extends \Codeception\Actor
{
    use _generated\FunctionalTesterActions;

    /**
     * @Given /^I am on "([^"]*)"$/
     */
    public function iAmOn($route)
    {
        $this->amOnPage($route);
    }

    /**
     * @When /^I click "([^"]*)"$/
     */
    public function iClick($btn)
    {
        $this->click($btn);
    }

    /**
     * @Then /^I should see the title "([^"]*)"\.$/
     */
    public function iShouldSeeTheTitle($title)
    {
        $this->seeInTitle($title);
    }

    /**
     * @Given /^I fill in "([^"]*)" with "([^"]*)"$/
     */
    public function iFillInWith($field, $value)
    {
        $this->fillField($field, $value);
    }

    /**
     * @Then /^I should see the "([^"]*)" in the "([^"]*)"\.$/
     */
    public function iShouldSeeTheInThe($value, $field)
    {
        $this->see($value, $field);
    }

    /**
     * @When /^I go to the page as an authorized user$/
     */
    public function iGoToThePageAsAnAuthorizedUser()
    {
        $this->loadFixtures(UserFixtures::class, false);
        $user = $this->grabEntityFromRepository(User::class);
        $this->amLoggedInAs($user);
    }

    /**
     * @When /^I go to the page as an unauthorized user$/
     */
    public function iGoToThePageAsAnUnauthorizedUser()
    {
        $this->loadFixtures(UserFixtures::class, false);
    }

    /**
     * @Given /^I tick "([^"]*)"$/
     */
    public function iTick($option)
    {
        $this->checkOption($option);
    }
}
