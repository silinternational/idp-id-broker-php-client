<?php
namespace Sil\Idp\IdBroker\Client\features\response;

use Behat\Gherkin\Node\PyStringNode;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
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
}
