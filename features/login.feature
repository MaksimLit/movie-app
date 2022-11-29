Feature: login
  In order to access the application
  As a user
  I need to be able to login to the app

  Background:
    When I go to the page as an unauthorized user

  Scenario: Try login if credential is correct
    Given I am on "/login"
    And I fill in "email" with "test@mail.com"
    And I fill in "password" with "123456"
    When I press "Login"
    Then I should see "Movie list"

  Scenario: Try login if credential is not correct
    Given I am on "/login"
    And I fill in "email" with "test@mail.com"
    And I fill in "password" with "12345"
    When I press "Login"
    Then I should see "Invalid credentials"