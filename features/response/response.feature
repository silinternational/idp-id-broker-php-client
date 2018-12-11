Feature: Handling responses from the ID Broker API

  Scenario: Handling a successful siteStatus call
    Given a call to "getSiteStatus" will return a 204 response
    When I call getSiteStatus
    Then an exception should NOT have been thrown

  Scenario: Handling a getSiteStatus call that triggers an exception
    Given a call to "getSiteStatus" will return a 500 with the following data:
      """
      {
        "name": "Internal Server Error",
        "message": "Some error message.",
        "code": 0,
        "status": 500
      }
      """
    When I call getSiteStatus
    Then an exception should have been thrown

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
        "locked": "no",
        "mfa": {
          "prompt": "no",
          "nag": "yes",
          "options": []
        }
      }
      """
    When I call authenticate with the necessary data
    Then the result SHOULD contain user information
      And the result should be an array
      And an exception should NOT have been thrown

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

  Scenario: Handling a successful new user authentication
    Given a call to "authenticate/newuser" will return a 200 with the following data:
      """
      {
        "employee_id": "12345",
        "first_name": "John",
        "last_name": "Smith",
        "display_name": "John Smith",
        "username": "john_smith",
        "email": "john_smith@example.com",
        "active": "yes",
        "locked": "no",
        "mfa": {
          "prompt": "no",
          "nag": "yes",
          "options": []
        }
      }
      """
    When I call authenticateNewUser with the necessary data
    Then the result SHOULD contain user information
    And the result should be an array
    And an exception should NOT have been thrown

  Scenario: Handling an unsuccessful new user authentication
    Given a call to "authenticate/newuser" will return a 400 with the following data:
      """
      {
        "name": "Bad Request",
        "message": "Some error message about an invalid login.",
        "code": 0,
        "status": 400
      }
      """
    When I call authenticateNewUser with the necessary data
    Then the result should be null
    And an exception should NOT have been thrown

  Scenario: Handling a new user authentication call that errors out
    Given a call to "authenticate/newuser" will return a 500 with the following data:
      """
      {
        "name": "Internal Server Error",
        "message": "Some error message.",
        "code": 0,
        "status": 500
      }
      """
    When I call authenticateNewUser with the necessary data
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
        "locked": "no",
        "mfa": {
          "prompt": "no",
          "nag": "yes",
          "options": []
        }
      }
      """
    When I call getUser with the necessary data
    Then the result SHOULD contain user information
      And the result should be an array
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
      And the result should be an array
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
      And the result should be an array

  Scenario: Handling a successful updateUser call
    Given a call to "updateUser" will return a 200 response
    When I call updateUser with the necessary data
    Then an exception should NOT have been thrown
      And the result should be an array

  Scenario: Handling a successful setPassword call
    Given a call to "setPassword" will return a 200 response
    When I call setPassword with the necessary data
    Then an exception should NOT have been thrown

  Scenario: Handling a rate-limited call to mfaVerify
    Given a call to "mfaVerify" will return a 429 response
    When I call mfaVerify with the necessary data
    Then an MFA rate-limit exception SHOULD have been thrown

  Scenario: Handling a "correct" response from mfaVerify
    Given a call to "mfaVerify" will return a 204 response
    When I call mfaVerify with the necessary data
    Then the result should be true

  Scenario: Handling a "wrong" response from mfaVerify
    Given a call to "mfaVerify" will return a 400 response
    When I call mfaVerify with the necessary data
    Then the result should be false

  Scenario: Handling a successful createMethod call
    Given a call to "createMethod" will return a 200 response
    When I call createMethod with the necessary data
    Then an exception should NOT have been thrown
    And the result should be an array

  Scenario: Handling a "correct" response from verifyMethod
    Given a call to "verifyMethod" will return a 200 response
    When I call verifyMethod with the necessary data
    Then the result should be an array
