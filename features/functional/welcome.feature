Feature: Welcoming developer
  As a Laravel developer
  In order to proberly begin a new project
  I need to be greeted upon arrival

  Scenario: Greeting developer on homepage
    Given I am logged in
    When I visit "/"
    Then I should see "Welcome to 36S Insight"

  Scenario: Using the admin section
    Given I am logged in
    When I visit "/admin/users"
    Then I should not see "Welcome to 36S Insight"