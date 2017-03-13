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
}
