Feature: register
  In order to create an account
  As a user
  I need to be able to register on the application

  Scenario: Try register
    Given I am on "/register"
    And I fill in "registration_form[email]" with "test@mail.com"
    And I fill in "registration_form[plainPassword][first]" with "123456"
    And I fill in "registration_form[plainPassword][second]" with "123456"
    And I tick "registration_form[agreeTerms]"
    When I click "Register"
    Then I should see the title "Movie list".
