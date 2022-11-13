Feature: search movie
  In order to add a movie to my list
  As a user
  I need to search the movie

  Background:
    When I go to the page as an authorized user

  Scenario: Try search movie
    Given I am on "/movie/search"
    And I fill in "name" with "Остров проклятых"
    When I click "search"
    Then I should see the "Остров проклятых" in the "h5".