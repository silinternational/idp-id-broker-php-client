Feature: Formatting requests for sending to the ID Broker API

  Scenario: Checking site status without validating the id broker ip address
    Given I am using a baseUri of "https://api.example.com/"
      And I have indicated not to validate the id broker ip
    When I call getSiteStatus
    Then the method should be "GET"
      And the url should be "https://api.example.com/site/status"

  Scenario: Checking site status with trusted id-broker
   Given I am using a trusted baseUri
     And I have indicated that I want the id broker ip to be validated
    When I call getSiteStatus
    Then the method should be "GET"
    And the url should be "https://trusted_host.org/site/status"

  Scenario: Checking client with untrusted id-broker
    Given I am using an untrusted baseUri
    When I create the idBrokerClient
    Then an UntrustedIp exception will be thrown

  Scenario: Checking client with a single trusted ip block value
    Given I have not indicated whether the id broker ip should be validated
      And I am using a single value for a trusted ip block
    When I create the idBrokerClient
    Then an InvalidArgument exception will be thrown

  Scenario: Checking client with the assert_valid_broker_ip not given and the trusted_ip_ranges value missing
    Given I am using a baseUri of "https://api.example.com/"
      And I have not indicated whether the id broker ip should be validated
    When I create the idBrokerClient
    Then an InvalidArgument exception will be thrown

  Scenario: Authentication
    Given I am using a baseUri of "https://api.example.com/"
      And I have provided an rpOrigin of "https://login.example.com"
      And I have indicated not to validate the id broker ip
      And I provide a "username" of "john_smith"
      And I provide a "password" of "correcthorsebatterystaple"
    When I call authenticate
    Then the method should be "POST"
      And the url should be "https://api.example.com/authentication?rpOrigin=https%3A%2F%2Flogin.example.com"
      And an authorization header should be present
      And the body should equal the following:
        """
        {
          "username": "john_smith",
          "password": "correcthorsebatterystaple"
        }
        """

  Scenario: Authentication for new user
    Given I am using a baseUri of "https://api.example.com/"
      And I have indicated not to validate the id broker ip
      And I provide an "invite" of "xyz789"
    When I call authenticateNewUser
    Then the method should be "POST"
      And the url should be "https://api.example.com/authentication"
      And an authorization header should be present
      And the body should equal the following:
        """
        {
          "invite": "xyz789"
        }
        """

  Scenario: Creating a user
    Given I am using a baseUri of "https://api.example.com/"
      And I have indicated not to validate the id broker ip
      And I provide an "employee_id" of "12345"
      And I provide a "first_name" of "John"
      And I provide a "last_name" of "Smith"
      And I provide a "username" of "john_smith"
      And I provide an "email" of "john_smith@example.com"
      And I provide a "locked" of "no"
      And I provide an "active" of "yes"
      And I provide a "manager_email" of "manager@example.com"
      And I provide a "require_mfa" of "no"
    When I call createUser
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
          "email": "john_smith@example.com",
          "locked": "no",
          "active": "yes",
          "manager_email": "manager@example.com",
          "require_mfa": "no"
        }
        """

  Scenario: Updating a user
    Given I am using a baseUri of "https://api.example.com/"
      And I have indicated not to validate the id broker ip
      And I provide an "employee_id" of "12345"
      And I provide a "display_name" of "Johnny"
      And I provide a "locked" of "yes"
      And I provide an "active" of "yes"
      And I provide a "manager_email" of "manager@example.com"
      And I provide a "require_mfa" of "yes"
    When I call updateUser
    Then the method should be "PUT"
      And the url should be "https://api.example.com/user/12345"
      And an authorization header should be present
      And the body should equal the following:
        """
        {
          "display_name": "Johnny",
          "locked": "yes",
          "active": "yes",
          "manager_email": "manager@example.com",
          "require_mfa": "yes"
        }
        """

  Scenario: Updating a user and sending null values
    Given I am using a baseUri of "https://api.example.com/"
      And I have indicated not to validate the id broker ip
      And I provide an "employee_id" of "12345"
      And I provide a "display_name" of "Johnny"
      And I provide a "locked" of "yes"
      And I provide an "active" of "yes"
      And I provide a "manager_email" of null
      And I provide a "require_mfa" of "yes"
    When I call updateUser
    Then the method should be "PUT"
      And the url should be "https://api.example.com/user/12345"
      And an authorization header should be present
      And the body should equal the following:
        """
        {
          "display_name": "Johnny",
          "locked": "yes",
          "active": "yes",
          "manager_email": null,
          "require_mfa": "yes"
        }
        """

  Scenario: Deactivating a user
    Given I am using a baseUri of "https://api.example.com/"
      And I have indicated not to validate the id broker ip
      And I provide an "employee_id" of "123"
    When I call deactivateUser
    Then the method should be "PUT"
      And the url should be "https://api.example.com/user/123"
      And an authorization header should be present
      And the body should be '{"active":"no"}'

  Scenario: Getting a user
    Given I am using a baseUri of "https://api.example.com/"
      And I have indicated not to validate the id broker ip
      And I provide an "employee_id" of "123"
    When I call getUser
    Then the method should be "GET"
      And the url should be 'https://api.example.com/user/123'
      And an authorization header should be present

  Scenario: Listing users
    Given I am using a baseUri of "https://api.example.com/"
      And I have indicated not to validate the id broker ip
    When I call listUsers
    Then the method should be "GET"
      And the url should be 'https://api.example.com/user'

  Scenario: Listing users, but limiting returned fields
    Given I am using a baseUri of "https://api.example.com/"
      And I have indicated not to validate the id broker ip
    When I call listUsers and ask for these fields:
        | fieldName   |
        | employee_id |
        | active      |
    Then the method should be "GET"
      And the url should be 'https://api.example.com/user?fields=employee_id%2Cactive'

  Scenario: Setting a password
    Given I am using a baseUri of "https://api.example.com/"
      And I have indicated not to validate the id broker ip
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

  Scenario: Validating a password
    Given I am using a baseUri of "https://api.example.com/"
    And I have indicated not to validate the id broker ip
    And I provide an "employee_id" of "123"
    And I provide a "password" of "correcthorsebatterystaple"
    When I call assessPassword
    Then the method should be "PUT"
    And the url should be 'https://api.example.com/user/123/password/assess'
    And an authorization header should be present
    And the body should equal the following:
        """
        {
          "password": "correcthorsebatterystaple"
        }
        """

  Scenario: Creating a recovery method
    Given I am using a baseUri of "https://api.example.com/"
    And I have indicated not to validate the id broker ip
    And I provide an "employee_id" of "12345"
    And I provide a "value" of "john_smith@example.com"
    When I call createMethod
    Then the method should be "POST"
    And the url should be "https://api.example.com/method"
    And an authorization header should be present
    And the body should equal the following:
        """
        {
          "employee_id": "12345",
          "value": "john_smith@example.com"
        }
        """

  Scenario: Deleting a recovery method
    Given I am using a baseUri of "https://api.example.com/"
    And I have indicated not to validate the id broker ip
    And I provide an "employee_id" of "123"
    And I provide a "uid" of "13579"
    When I call deleteMethod
    Then the method should be "DELETE"
    And the url should be 'https://api.example.com/method/13579'
    And an authorization header should be present
    And the body should equal the following:
        """
        {
          "employee_id": "123"
        }
        """

  Scenario: Getting a recovery method
    Given I am using a baseUri of "https://api.example.com/"
    And I have indicated not to validate the id broker ip
    And I provide an "employee_id" of "123"
    And I provide a "uid" of "13579"
    When I call getMethod
    Then the method should be "GET"
    And the url should be 'https://api.example.com/method/13579'
    And an authorization header should be present
    And the body should equal the following:
        """
        {
          "employee_id": "123"
        }
        """

  Scenario: Listing recovery methods
    Given I am using a baseUri of "https://api.example.com/"
    And I have indicated not to validate the id broker ip
    And I provide an "employee_id" of "123"
    When I call listMethod
    Then the method should be "GET"
    And the url should be 'https://api.example.com/user/123/method'
    And an authorization header should be present

  Scenario: Verifying a recovery method
    Given I am using a baseUri of "https://api.example.com/"
    And I have indicated not to validate the id broker ip
    And I provide an "employee_id" of "123"
    And I provide a "uid" of "13579"
    And I provide a "code" of "24680"
    When I call verifyMethod
    Then the method should be "PUT"
    And the url should be 'https://api.example.com/method/13579/verify'
    And an authorization header should be present
    And the body should equal the following:
        """
        {
          "employee_id": "123",
          "code": "24680"
        }
        """

  Scenario: Resending a recovery method
    Given I am using a baseUri of "https://api.example.com/"
    And I have indicated not to validate the id broker ip
    And I provide an "employee_id" of "123"
    And I provide a "uid" of "13579"
    When I call resendMethod
    Then the method should be "PUT"
    And the url should be 'https://api.example.com/method/13579/resend'
    And an authorization header should be present
    And the body should equal the following:
        """
        {
          "employee_id": "123"
        }
        """

  Scenario: Creating an mfa option
    Given I am using a baseUri of "https://api.example.com/"
      And I have indicated not to validate the id broker ip
      And I have provided an rpOrigin of "https://login.example.com"
      And I provide an "employee_id" of "12345"
      And I provide a "type" of "webauthn"
      And I provide a "label" of "Blue security key"
    When I call mfaCreate
    Then the method should be "POST"
      And the url should be "https://api.example.com/mfa?rpOrigin=https%3A%2F%2Flogin.example.com"
      And an authorization header should be present
      And the body should equal the following:
        """
        {
          "employee_id": "12345",
          "type": "webauthn",
          "label": "Blue security key"
        }
        """

  Scenario: Deleting an mfa option
    Given I am using a baseUri of "https://api.example.com/"
      And I have indicated not to validate the id broker ip
      And I provide an "employee_id" of "123"
      And I provide an "id" of "789"
    When I call deleteMfa
    Then the method should be "DELETE"
      And the url should be 'https://api.example.com/mfa/789'
      And an authorization header should be present
      And the body should equal the following:
        """
        {
          "employee_id": "123"
        }
        """

  Scenario: Listing mfa options
    Given I am using a baseUri of "https://api.example.com/"
      And I have provided an rpOrigin of "https://login.example.com"
      And I have indicated not to validate the id broker ip
      And I provide an "employee_id" of "123"
    When I call mfaList
    Then the method should be "GET"
      And the url should be "https://api.example.com/user/123/mfa?rpOrigin=https%3A%2F%2Flogin.example.com"
      And an authorization header should be present

  Scenario: Update an mfa option
    Given I am using a baseUri of "https://api.example.com/"
      And I have indicated not to validate the id broker ip
      And I provide an "id" of "789"
      And I provide an "employee_id" of "123"
      And I provide an "label" of "Purple security key"
    When I call mfaUpdate
    Then the method should be "PUT"
      And the url should be 'https://api.example.com/mfa/789'
      And an authorization header should be present
      And the body should equal the following:
        """
        {
          "employee_id": "123",
          "label": "Purple security key"
        }
        """

  Scenario: Verifying an mfa option
    Given I am using a baseUri of "https://api.example.com/"
      And I have provided an rpOrigin of "https://login.example.com"
      And I have indicated not to validate the id broker ip
      And I provide an "id" of "789"
      And I provide an "employee_id" of "123"
      And I provide a "value" of "01234987"
    When I call mfaVerify
    Then the method should be "POST"
      And the url should be 'https://api.example.com/mfa/789/verify?rpOrigin=https%3A%2F%2Flogin.example.com'
      And an authorization header should be present
      And the body should equal the following:
        """
        {
          "employee_id": "123",
          "value": "01234987"
        }
        """


  Scenario: Verifying an mfa registration
    Given I am using a baseUri of "https://api.example.com/"
    And I have provided an rpOrigin of "https://login.example.com"
    And I have indicated not to validate the id broker ip
    And I provide an "id" of "789"
    And I provide an "employee_id" of "123"
    And I provide a "value" of "01234987"
    When I call mfaVerifyRegistration
    Then the method should be "POST"
    And the url should be 'https://api.example.com/mfa/789/verify/registration?rpOrigin=https%3A%2F%2Flogin.example.com'
    And an authorization header should be present
    And the body should equal the following:
        """
        {
          "employee_id": "123",
          "value": "01234987"
        }
        """
