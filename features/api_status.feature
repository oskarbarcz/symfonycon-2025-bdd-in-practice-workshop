Feature: As a Symfony Workshop attendee
  I want to check it trainer's API is working

  Scenario: I test status endpoint
    When I send a GET request to "/api/post"
    Then the response code should be 200