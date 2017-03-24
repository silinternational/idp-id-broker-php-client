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
      And the result should NOT contain an error message

  Scenario: Handling an unsuccessful authentication
    Given a call to "authenticate" will return a 400 with the following data:
      """
      {
        "name": "Bad Request",
        "message": "Some error message about an invalid login.",
        "code": 0,
        "status": 400
      }
      """
    When I call authenticate with the necessary data
    Then the result should NOT contain user information
      And the result SHOULD contain an error message

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
      And the result SHOULD contain an error message

  Scenario: Handling an authentication that returns an unexpected response
    Given a call to "authenticate" will return a 404 with the following data:
      """
      {
        "name": "Not Found",
        "message": "Some error message.",
        "code": 0,
        "status": 404
      }
      """
    When I call authenticate with the necessary data
    Then the result should NOT contain user information
      And the result SHOULD contain an error message
