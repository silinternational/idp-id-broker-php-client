Feature: Handling responses from the ID Broker API

  Scenario: Getting an existing user
    Given a call to "getUser" will return a 200 response with the following data:
        """
        {
          "employee_id": "123",
          "first_name": "John",
          "last_name": "Smith",
          "display_name": "John Smith",
          "username": "john_smith",
          "email": "john_smith@example.com",
          "locked": "no"
        }
        """
    When I call it with an "employee_id" of "123"
    Then the status code should be 200
      And the resulting "employee_id" should be "123"
      And the resulting "first_name" should be "John"
      And the resulting "last_name" should be "Smith"
      And the resulting "display_name" should be "John Smith"
      And the resulting "username" should be "john_smith"
      And the resulting "email" should be "john_smith@example.com"
      And the resulting "locked" should be "no"

  Scenario: Getting a non-existent user
    Given a call to "getUser" will return a 404 response
    When I call it with an "employee_id" of "123"
    Then the status code should be 404
      And the response should not contain any user information

  Scenario: Handling a successful authentication
    Given a call to authenticate will be successful
    When I call authenticate with the necessary data
    Then the status code should be 200
      And the response should contain information about that user

  Scenario: Handling an unsuccessful authentication
    Given a call to authenticate will be rejected
    When I call authenticate with the necessary data
    Then the status code should be 400
      And the response should not contain any user information

  Scenario: Handling an authentication that returns an unexpected response
    Given a call to "authenticate" will return a 404 response
    When I call it with a "username" and a "password"
    Then the status code should be 404
      And the response should not contain any user information
