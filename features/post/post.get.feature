Feature: Getting one posting

  Scenario: As a user I want to see a post
    When I send a GET request to "/api/post/f72dc06d-a50b-4f87-9ac6-303fe18e7270"
    Then the response code should be 200
    And the response body should be:
    """
    {
      "id": "f72dc06d-a50b-4f87-9ac6-303fe18e7270",
      "title": "My First Post",
      "content": "This is the content of my first post.",
      "tags": ["php", "symfony", "api"],
      "createdAt": "2025-01-01T00:00:00+00:00"
    }
    """

  Scenario: As a user I cannot get posting with unknown ID
    When I send a GET request to "/api/post/4ef6e203-4e2e-494b-bb89-1a76adf193b5"
    Then the response code should be 404
    And the response body should be:
    """
    {
      "error": "Post not found"
    }
    """