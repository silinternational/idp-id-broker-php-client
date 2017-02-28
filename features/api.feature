Feature: Call the ID Broker API

  Scenario: Authentication
    Given I am using a baseUri of "https://api.example.com/"
      And I provide a username of "abc"
      And I provide a password of "def"
    When I call authenticate
    Then the method should be "POST"
      And the url should be "https://api.example.com/authentication"
      And the body should be '{"username":"abc","password":"def"}'
