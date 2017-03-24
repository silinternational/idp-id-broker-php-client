Feature: Formatting requests for sending to the ID Broker API

  Scenario: Activating a user
    Given I am using a baseUri of "https://api.example.com/"
      And I provide an "employee_id" of "123"
    When I call activateUser
    Then the method should be "PUT"
      And the url should be "https://api.example.com/user/123"
      And an authorization header should be present
      And the body should be '{"active":"yes"}'

  Scenario: Authentication
    Given I am using a baseUri of "https://api.example.com/"
      And I provide a "username" of "john_smith"
      And I provide a "password" of "correcthorsebatterystaple"
    When I call authenticate
    Then the method should be "POST"
      And the url should be "https://api.example.com/authentication"
      And an authorization header should be present
      And the body should equal the following:
        """
        {
          "username": "john_smith",
          "password": "correcthorsebatterystaple"
        }
        """

  Scenario: Creating/updating a user
    Given I am using a baseUri of "https://api.example.com/"
      And I provide an "employee_id" of "12345"
      And I provide a "first_name" of "John"
      And I provide a "last_name" of "Smith"
      And I provide a "username" of "john_smith"
      And I provide an "email" of "john_smith@example.com"
    When I call createOrUpdateUser
    Then the method should be "POST"
      And the url should be "https://api.example.com/user"
      And an authorization header should be present
      And the body should equal the following:
        """
        {
          "employee_id": "12345",
          "first_name": "John",
          "last_name": "Smith",
          "username": "john_smith",
          "email": "john_smith@example.com"
        }
        """

  Scenario: Deactivating a user
    Given I am using a baseUri of "https://api.example.com/"
      And I provide an "employee_id" of "123"
    When I call deactivateUser
    Then the method should be "PUT"
      And the url should be "https://api.example.com/user/123"
      And an authorization header should be present
      And the body should be '{"active":"no"}'

  # TODO: Do we need this? If so, how should we specify it in the RAML file?
  Scenario: Finding users
    Given I am using a baseUri of "https://api.example.com/"
      And I provide a "username" of "abc"
    When I call findUser
    Then the method should be "GET"
      And the url should be 'https://api.example.com/user?username=abc'
      And an authorization header should be present

  Scenario: Getting a user
    Given I am using a baseUri of "https://api.example.com/"
      And I provide an "employee_id" of "123"
    When I call getUser
    Then the method should be "GET"
      And the url should be 'https://api.example.com/user/123'
      And an authorization header should be present

  Scenario: Listing users
    Given I am using a baseUri of "https://api.example.com/"
    When I call listUsers
    Then the method should be "GET"
      And the url should be 'https://api.example.com/user'

  Scenario: Setting a password
    Given I am using a baseUri of "https://api.example.com/"
      And I provide an "employee_id" of "123"
      And I provide a "password" of "correcthorsebatterystaple"
    When I call setPassword
    Then the method should be "PUT"
      And the url should be 'https://api.example.com/user/123/password'
      And an authorization header should be present
      And the body should equal the following:
        """
        {
          "password": "correcthorsebatterystaple"
        }
        """
