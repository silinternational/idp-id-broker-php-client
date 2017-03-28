<?php
namespace Sil\Idp\IdBroker\Client\features\response;

use Behat\Gherkin\Node\PyStringNode;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Assert;
use Sil\Idp\IdBroker\Client\IdBrokerClient;

/**
 * Defines application features from the specific context.
 */
class ResponseContext implements Context
{
    private $userInfoFields = [
        'employee_id',
        'first_name',
        'last_name',
        'display_name',
        'username',
        'email',
        'active',
        'locked',
    ];
    private $methodName;
    private $response = null;
    private $result;
    
    protected function getHttpClientHandlerForTests()
    {
        Assert::assertNotEmpty(
            $this->response,
            'You need to define the response before you can pretend to call the API.'
        );
        $mockHandler = new MockHandler([$this->response]);
        return HandlerStack::create($mockHandler);
    }
    
    /**
     * @return IdBrokerClient
     */
    protected function getIdBrokerClient()
    {
        return new IdBrokerClient('https://api.example.com/', 'DummyAccessToken', [
            'http_client_options' => [
                'handler' => $this->getHttpClientHandlerForTests(),
            ],
        ]);
    }
    
    /**
     * @Given a call to :methodName will return a :statusCode with the following data:
     */
    public function aCallToWillReturnAWithTheFollowingData(
        $methodName,
        $statusCode,
        PyStringNode $responseData
    ) {
        $this->methodName = $methodName;
        $this->response = new Response($statusCode, [], (string)$responseData);
    }
    
    /**
     * @Given a call to :methodName will return a :statusCode response
     */
    public function aCallToWillReturnAResponse($methodName, $statusCode)
    {
        $this->methodName = $methodName;
        $this->response = new Response($statusCode);
    }
    
    /**
     * @When I call authenticate with the necessary data
     */
    public function iCallAuthenticateWithTheNecessaryData()
    {
        $this->result = $this->getIdBrokerClient()->authenticate([
            'username' => 'john_smith',
            'password' => 'dummy password',
        ]);
    }
    
    /**
     * @Then the result should NOT contain user information
     */
    public function theResultShouldNotContainUserInformation()
    {
        foreach ($this->userInfoFields as $fieldName) {
            Assert::assertArrayNotHasKey($fieldName, $this->result);
        }
    }
    
    /**
     * @Then the result SHOULD contain user information
     */
    public function theResultShouldContainUserInformation()
    {
        foreach ($this->userInfoFields as $fieldName) {
            Assert::assertArrayHasKey($fieldName, $this->result);
        }
    }

    /**
     * @Then the result should NOT contain an error message
     */
    public function theResultShouldNotContainAnErrorMessage()
    {
        Assert::assertArrayNotHasKey('message', $this->result);
    }
    
    /**
     * @Then the result SHOULD contain an error message
     */
    public function theResultShouldContainAnErrorMessage()
    {
        Assert::assertArrayHasKey('message', $this->result);
    }

    /**
     * @When I call getUser with the necessary data
     */
    public function iCallGetuserWithTheNecessaryData()
    {
        $this->result = $this->getIdBrokerClient()->getUser([
            'employee_id' => '123245',
        ]);
    }

    /**
     * @When I call listUsers with the necessary data
     */
    public function iCallListusersWithTheNecessaryData()
    {
        $this->result = $this->getIdBrokerClient()->listUsers();
    }

    /**
     * @Then the result SHOULD contain a list of users' information
     */
    public function theResultShouldContainAListOfUsersInformation()
    {
        foreach ($this->result as $resultEntry) {
            $foundSomeUserInfo = false;
            foreach ($this->userInfoFields as $fieldName) {
                if (array_key_exists($fieldName, $resultEntry)) {
                    $foundSomeUserInfo = true;
                    break;
                }
            }
            Assert::assertTrue($foundSomeUserInfo);
        }
    }

    /**
     * @Then the result should NOT contain a list of users' information
     */
    public function theResultShouldNotContainAListOfUsersInformation()
    {
        foreach ($this->result as $resultEntry) {
            if ( ! is_array($resultEntry)) {
                continue;
            }
            foreach ($this->userInfoFields as $fieldName) {
                if (array_key_exists($fieldName, $resultEntry)) {
                    Assert::fail();
                }
            }
        }
    }

    /**
     * @When I call createUser with the necessary data
     */
    public function iCallCreateuserWithTheNecessaryData()
    {
        $this->result = $this->getIdBrokerClient()->createUser([
            'employee_id' => '12345',
            'first_name' => 'John',
            'last_name' => 'Smith',
            'username' => 'john_smith',
            'email' => 'john_smith@example.com',
        ]);
    }

    /**
     * @When I call updateUser with the necessary data
     */
    public function iCallUpdateuserWithTheNecessaryData()
    {
        $this->result = $this->getIdBrokerClient()->updateUser([
            'employee_id' => '12345',
            'first_name' => 'John',
        ]);
    }

    /**
     * @When I call setPassword with the necessary data
     */
    public function iCallSetpasswordWithTheNecessaryData()
    {
        $this->result = $this->getIdBrokerClient()->setPassword([
            'employee_id' => '12345',
            'password' => 'correcthorsebatterystaple',
        ]);
    }
}
