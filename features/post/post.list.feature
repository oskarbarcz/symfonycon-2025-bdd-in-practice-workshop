Feature: Getting posting list

  Scenario: As a user I want to see all posts
    When I send a GET request to "/api/post"
    Then the response code should be 200
    And the response body should be:
    """
    [
      {
        "id": "f72dc06d-a50b-4f87-9ac6-303fe18e7270",
        "title": "My First Post",
        "content": "This is the content of my first post.",
        "tags": ["php", "symfony", "api"],
        "createdAt": "2025-01-01T00:00:00+00:00"
      }
    ]
    """
