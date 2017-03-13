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
     * @Given a call to :methodName will return a :statusCode response with the following data:
     */
    public function aCallToWillReturnAResponseWithTheFollowingData(
        $methodName,
        $statusCode,
        PyStringNode $responseData
    ) {
        $this->methodName = $methodName;
        $this->response = new Response($statusCode, [], (string)$responseData);
    }

    /**
     * @Then the status code should be :expectedStatusCode
     */
    public function theStatusCodeShouldBe($expectedStatusCode)
    {
        Assert::assertNotEmpty($this->result);
        Assert::assertEquals($this->result['statusCode'], $expectedStatusCode);
    }

    /**
     * @When I call getUser
     */
    public function iCallGetuser()
    {
        $this->result = $this->getIdBrokerClient()->getUser([
            'employee_id' => '123',
        ]);
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
     * @When I call it with a(n) :field of :value
     */
    public function iCallItWithAnOf($field, $value)
    {
        $methodName = $this->methodName;
        $this->result = $this->getIdBrokerClient()->$methodName([
            $field => $value,
        ]);
    }

    /**
     * @Then the resulting :field should be :value
     */
    public function theResultingShouldBe($field, $value)
    {
        Assert::assertNotEmpty($this->result);
        Assert::assertSame($this->result[$field], $value);
    }

    /**
     * @Given a call to authenticate will be successful
     */
    public function aCallToAuthenticateWillBeSuccessful()
    {
        $this->response = new Response(200, [], \json_encode([
          'employee_id' => '123',
          'first_name' => 'John',
          'last_name' => 'Smith',
          'display_name' => 'John Smith',
          'username' => 'john_smith',
          'email' => 'john_smith@example.com',
          'locked' => 'no'
        ]));
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
     * @Then the response should contain information about that user
     */
    public function theResponseShouldContainInformationAboutThatUser()
    {
        Assert::assertNotEmpty($this->result);
        Assert::assertSame($this->result['email'], 'john_smith@example.com');
    }

    /**
     * @Given a call to authenticate will be rejected
     */
    public function aCallToAuthenticateWillBeRejected()
    {
        $this->response = new Response(400);
    }

    /**
     * @Then the response should not contain any user information
     */
    public function theResponseShouldNotContainAnyUserInformation()
    {
        Assert::assertArrayNotHasKey('employee_id', $this->result);
        Assert::assertArrayNotHasKey('first_name', $this->result);
        Assert::assertArrayNotHasKey('last_name', $this->result);
        Assert::assertArrayNotHasKey('display_name', $this->result);
        Assert::assertArrayNotHasKey('username', $this->result);
        Assert::assertArrayNotHasKey('email', $this->result);
        Assert::assertArrayNotHasKey('locked', $this->result);
    }

    /**
     * @When I call it with a :fieldOne and a :fieldTwo
     */
    public function iCallItWithAAndA($fieldOne, $fieldTwo)
    {
        Assert::assertNotEmpty($this->methodName);
        $methodName = $this->methodName;
        $this->result = $this->getIdBrokerClient()->$methodName([
            $fieldOne => 'dummy value one',
            $fieldTwo => 'dummy value two',
        ]);
    }
}
