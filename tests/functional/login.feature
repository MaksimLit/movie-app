Feature: login
  In order to access the application
  As a user
  I need to be able to login to the app

  Background:
    When I go to the page as an unauthorized user

  Scenario: Try login
    Given I am on "/login"
    And I fill in "email" with "test@mail.com"
    And I fill in "password" with "123456"
    When I click "Login"
    Then I should see the title "Movie list".
