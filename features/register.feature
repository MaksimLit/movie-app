Feature: register
  In order to create an account
  As a user
  I need to be able to register on the application

  Scenario: Try register successfully
    Given I am on "/register"
    When I fill in "registration_form[email]" with "test@mail.com"
    And I fill in "registration_form[plainPassword][first]" with "123456"
    And I fill in "registration_form[plainPassword][second]" with "123456"
    And I check "registration_form[agreeTerms]"
    And I press "Register"
    Then I should see "Movie list"

  Scenario: Try register if password too short
    Given I am on "/register"
    When I fill in "registration_form[email]" with "test@mail.com"
    And I fill in "registration_form[plainPassword][first]" with "12345"
    And I fill in "registration_form[plainPassword][second]" with "12345"
    And I check "registration_form[agreeTerms]"
    And I press "Register"
    Then I should see "Your password should be at least 6 characters"

  Scenario: Try register if repeat password do not match
    Given I am on "/register"
    When I fill in "registration_form[email]" with "test@mail.com"
    And I fill in "registration_form[plainPassword][first]" with "123456"
    And I fill in "registration_form[plainPassword][second]" with "12345"
    And I check "registration_form[agreeTerms]"
    And I press "Register"
    Then I should see "he values do not match"