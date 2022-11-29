Feature: search movie
  In order to add a movie to my list
  As a user
  I need to search the movie

  Scenario: Try search movie
    Given I am logged in as a user
    And I am on "/movie/search"
    And I fill in "name" with "Остров проклятых"
    When I press "search"
    Then I should see "Остров проклятых"

  Scenario: Try search movie
    Given I am logged in as a user
    And I am on "/movie/search"
    And I fill in "name" with "Название несуществующего фильма"
    When I press "search"
    Then I should see "Nothing found"