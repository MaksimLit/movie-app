Feature: register
  In order to create an account
  As a user
  I need to be able to register on the application

  Scenario: Try register successfully
    Given I am on "/register"
    When I fill in "registration_form[email]" with "test@mail.com"
    And I fill in "registration_form[plainPassword][first]" with "123456"
    And I fill in "registration_form[plainPassword][second]" with "123456"
    And I tick "registration_form[agreeTerms]"
    And I click "Register"
    Then I should see the title "Movie list"

  Scenario: Try register if password too short
    Given I am on "/register"
    When I fill in "registration_form[email]" with "test@mail.com"
    And I fill in "registration_form[plainPassword][first]" with "12345"
    And I fill in "registration_form[plainPassword][second]" with "12345"
    And I tick "registration_form[agreeTerms]"
    And I click "Register"
    Then I see "Your password should be at least 6 characters"

  Scenario: Try register if repeat password do not match
    Given I am on "/register"
    When I fill in "registration_form[email]" with "test@mail.com"
    And I fill in "registration_form[plainPassword][first]" with "12345"
    And I fill in "registration_form[plainPassword][second]" with "1234"
    And I tick "registration_form[agreeTerms]"
    And I click "Register"
    Then I see "The values do not match"
