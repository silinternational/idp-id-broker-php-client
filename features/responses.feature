Feature: Handling responses from the ID Broker API

  Scenario: Getting an existing user
    Given the following user exists in the ID Broker:
        | employee_id | username   |
        | 123         | john_smith |
      And I provide an "employee_id" of "123"
    When I call getUser
    Then the status code should be 200
      And the response should be a "User" object
