Feature: Handling responses from the ID Broker API

  Scenario: Handling a successful authentication
    Given a call to "authenticate" will return a 200 with the following data:
      """
      {
        "employee_id": "12345",
        "first_name": "John",
        "last_name": "Smith",
        "display_name": "John Smith",
        "username": "john_smith",
        "email": "john_smith@example.com",
        "active": "yes",
        "locked": "no"
      }
      """
    When I call authenticate with the necessary data
    Then the result SHOULD contain user information
      And an exception should NOT have been thrown

  Scenario: Handling an unsuccessful authentication
    Given a call to "authenticate" will return a 422 with the following data:
      """
      {
        "name": "Bad Request",
        "message": "Some error message about an invalid login.",
        "code": 0,
        "status": 422
      }
      """
    When I call authenticate with the necessary data
    Then the result should be null
      And an exception should NOT have been thrown

  Scenario: Handling an authentication call that errors out
    Given a call to "authenticate" will return a 500 with the following data:
      """
      {
        "name": "Internal Server Error",
        "message": "Some error message.",
        "code": 0,
        "status": 500
      }
      """
    When I call authenticate with the necessary data
    Then the result should NOT contain user information
      And an exception SHOULD have been thrown

  Scenario: Handling a successful getUser call
    Given a call to "getUser" will return a 200 with the following data:
      """
      {
        "employee_id": "12345",
        "first_name": "John",
        "last_name": "Smith",
        "display_name": "John Smith",
        "username": "john_smith",
        "email": "john_smith@example.com",
        "active": "yes",
        "locked": "no"
      }
      """
    When I call getUser with the necessary data
    Then the result SHOULD contain user information
      And an exception should NOT have been thrown

  Scenario: Handling a getUser call for a non-existent user
    Given a call to "getUser" will return a 204 response
    When I call getUser with the necessary data
    Then the result should be null
      And an exception should NOT have been thrown

  Scenario: Handling a successful listUsers call
    Given a call to "listUsers" will return a 200 with the following data:
      """
      [
        {"employee_id": "11111", "active": "yes"},
        {"employee_id": "22222", "active": "no"}
      ]
      """
    When I call listUsers with the necessary data
    Then the result SHOULD contain a list of users' information
      And an exception should NOT have been thrown

  Scenario: Handling a listUsers call that errors out
    Given a call to "listUsers" will return a 500 with the following data:
      """
      {
        "name": "Internal Server Error",
        "message": "Some error message.",
        "code": 0,
        "status": 500
      }
      """
    When I call listUsers with the necessary data
    Then the result should NOT contain a list of users' information
      And an exception SHOULD have been thrown

  Scenario: Handling a successful createUser call
    Given a call to "createUser" will return a 200 response
    When I call createUser with the necessary data
    Then an exception should NOT have been thrown

  Scenario: Handling a successful updateUser call
    Given a call to "updateUser" will return a 200 response
    When I call updateUser with the necessary data
    Then an exception should NOT have been thrown

  Scenario: Handling a successful setPassword call
    Given a call to "setPassword" will return a 200 response
    When I call setPassword with the necessary data
    Then an exception should NOT have been thrown
