Feature: Handling responses from the ID Broker API

  Scenario: Getting an existing user
    Given a call to "getUser" will return a 200 response with following data:
        """
        {
          "employee_id":"123",
          "first_name": "John",
          "last_name": "Smith",
          "display_name": "John Smith",
          "username": "john_smith",
          "email": "john_smith@example.com",
          "locked": "no"
        }
        """
    When I call getUser
    Then the status code should be 200
      And the response should be a "User" object
