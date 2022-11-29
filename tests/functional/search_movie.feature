Feature: search movie
  In order to add a movie to my list
  As a user
  I need to search the movie

  Background:
    When I go to the page as an authorized user

  Scenario: Try show search result if movie is found
    Given I am on "/movie/search"
    And I fill in "name" with "Остров проклятых"
    When I click "search"
    Then I should see the "Остров проклятых" in the "h5".

  Scenario: Try show search result if movie is not found
    Given I am on "/movie/search"
    And I fill in "name" with "Название несуществующего фильма"
    When I click "search"
    Then I see "Nothing found"