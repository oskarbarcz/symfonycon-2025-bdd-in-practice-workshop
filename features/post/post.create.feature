Feature: Post creation

  Scenario: As a user I want to create a post
    When I send a POST request to "/api/post" with body:
    """json
    {
      "title": "My first post",
      "content": "This is the content of my first post",
      "tags": ["test"]
    }
    """
    Then the response code should be 201
    And the response body should be:
    """json
    {
      "id": "@uuid",
      "title": "My first post",
      "content": "This is the content of my first post",
      "tags": ["test"],
      "createdAt": "@date('within 1 minute from now')"
    }
    """
    And I see in the database entity Post with:
    And I see that a mail is sent to recipient "test@test.com"
    And I see...

  Scenario: As a user I cannot create a post with empty body
    When I send a POST request to "/api/post" with body:
    """json
    {}
    """
    Then the response code should be 422
    And the response body should be:
    """json
    {
       "type": "https:\/\/symfony.com\/errors\/validation",
       "title": "Validation Failed",
       "status": 422,
       "detail": "title: This value should not be blank.\ncontent: This value should not be blank.\ntags: This value should not be blank.",
       "violations": [
          {
             "propertyPath": "title",
             "title": "This value should not be blank.",
             "template": "This value should not be blank.",
             "parameters": {
                 "{{ value }}": "null"
             },
             "type": "urn:uuid:c1051bb4-d103-4f74-8988-acbcafc7fdc3"
          },
          {
             "propertyPath": "content",
             "title": "This value should not be blank.",
             "template": "This value should not be blank.",
             "parameters": {
                 "{{ value }}": "null"
             },
             "type": "urn:uuid:c1051bb4-d103-4f74-8988-acbcafc7fdc3"
          },
          {
             "propertyPath": "tags",
             "title": "This value should not be blank.",
             "template": "This value should not be blank.",
             "parameters": {
                 "{{ value }}": "null"
             },
             "type": "urn:uuid:c1051bb4-d103-4f74-8988-acbcafc7fdc3"
          }
      ]
    }
    """