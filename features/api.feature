Feature: Call the ID Broker API

  Scenario: Activating a user
    Given I am using a baseUri of "https://api.example.com/"
      And I provide an employee_id of "123"
    When I call activateUser
    Then the method should be "PUT"
      And the url should be "https://api.example.com/user/123"
      And the body should be '{"active":"yes"}'

  Scenario: Authentication
    Given I am using a baseUri of "https://api.example.com/"
      And I provide a username of "abc"
      And I provide a password of "def"
    When I call authenticate
    Then the method should be "POST"
      And the url should be "https://api.example.com/authentication"
      And the body should be '{"username":"abc","password":"def"}'

  Scenario: Creating a user
    Given I am using a baseUri of "https://api.example.com/"
      And I provide an employee_id of "john_smith"
      And I provide a first name of "John"
      And I provide a last name of "Smith"
      And I provide a username of "john_smith"
      And I provide an email of "john_smith@example.com"
    When I call createUser
    Then the method should be "POST"
      And the url should be "https://api.example.com/user"

  Scenario: Deactivating a user
    Given I am using a baseUri of "https://api.example.com/"
      And I provide an employee_id of "123"
    When I call deactivateUser
    Then the method should be "PUT"
      And the url should be "https://api.example.com/user/123"
      And the body should be '{"active":"no"}'

  Scenario: Finding users
    Given I am using a baseUri of "https://api.example.com/"
      And I provide a username of "abc"
    When I call findUsers
    Then the method should be "GET"
      And the url should be 'https://api.example.com/user?username=abc'

  Scenario: Getting a user
    Given I am using a baseUri of "https://api.example.com/"
      And I provide an employee_id of "123"
    When I call getUser
    Then the method should be "GET"
      And the url should be 'https://api.example.com/user/123'

  Scenario: Listing users
    Given I am using a baseUri of "https://api.example.com/"
    When I call listUsers
    Then the method should be "GET"
      And the url should be 'https://api.example.com/user'

  Scenario: Updating a user
    Given I am using a baseUri of "https://api.example.com/"
      And I provide an employee_id of "123"
    When I call updateUser
    Then the method should be "PUT"
      And the url should be 'https://api.example.com/user/123'
      And the body should contain json
      And the body should not contain a "password" field

  Scenario: Setting a password
    Given I am using a baseUri of "https://api.example.com/"
      And I provide an employee_id of "123"
      And I provide a password of "def"
    When I call setPassword
    Then the method should be "PUT"
      And the url should be 'https://api.example.com/user/123/password'
      And the body should be '{"password":"def"}'
