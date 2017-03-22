<?php
namespace Sil\Idp\IdBroker\Client\features\request;

use Behat\Behat\Context\Context;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Constraint\IsJson;
use Sil\Idp\IdBroker\Client\IdBrokerClient;

/**
 * Defines application features from the specific context.
 */
class RequestContext implements Context
{
    private $baseUri;
    private $requestData = [];
    private $requestHistory = [];
    
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }
    
    protected function assertSame($expected, $actual)
    {
        Assert::assertSame($expected, $actual, sprintf(
            "Expected %s,\n"
            . "     not %s.",
            var_export($expected, true),
            var_export($actual, true)
        ));
    }
    
    protected function getHttpClientHandlerForTests()
    {
        $mockHandler = new MockHandler([
            new Response(),
        ]);
        $handlerStack = HandlerStack::create($mockHandler);

        // Add a history middleware to the handler stack.
        $historyMiddleware = Middleware::history($this->requestHistory);
        $handlerStack->push($historyMiddleware);
        
        return $handlerStack;
    }
    
    /**
     * @return IdBrokerClient
     */
    protected function getIdBrokerClient()
    {
        return new IdBrokerClient($this->baseUri, 'DummyAccessToken', [
            'http_client_options' => [
                'handler' => $this->getHttpClientHandlerForTests(),
            ],
        ]);
    }
    
    /**
     * @return Request
     */
    protected function getRequestFromHistory()
    {
        return $this->requestHistory[0]['request'];
    }

    /**
     * @Given I am using a baseUri of :baseUri
     */
    public function iAmUsingABaseuriOf($baseUri)
    {
        $this->baseUri = $baseUri;
    }

    /**
     * @Then the url should be :expectedUri
     */
    public function theUrlShouldBe($expectedUri)
    {
        $request = $this->getRequestFromHistory();
        $actualUri = (string)$request->getUri();
        $this->assertSame($expectedUri, $actualUri);
    }

    /**
     * @Then the body should be :expectedBodyText
     */
    public function theBodyShouldBe($expectedBodyText)
    {
        $request = $this->getRequestFromHistory();
        $actualBodyText = (string)$request->getBody();
        $this->assertSame($expectedBodyText, $actualBodyText);
    }

    /**
     * @Then the body should contain json
     */
    public function theBodyShouldContainJson()
    {
        $request = $this->getRequestFromHistory();
        $bodyText = (string)$request->getBody();
        Assert::assertThat($bodyText, new IsJson());
    }
    
    /**
     * @Then the method should be :expectedMethod
     */
    public function theMethodShouldBe($expectedMethod)
    {
        $request = $this->getRequestFromHistory();
        $actualMethod = $request->getMethod();
        $this->assertSame($expectedMethod, $actualMethod);
    }

    /**
     * @When I call :methodName
     */
    public function iCall($methodName)
    {
        $this->getIdBrokerClient()->$methodName($this->requestData);
    }

    /**
     * @Then the body should not contain a :fieldName field
     */
    public function theBodyShouldNotContainAField($fieldName)
    {
        $request = $this->getRequestFromHistory();
        $bodyText = (string)$request->getBody();
        Assert::assertNotContains($fieldName, $bodyText);
    }

    /**
     * @Given I provide a first name of :firstName
     */
    public function iProvideAFirstNameOf($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @Given I provide a last name of :lastName
     */
    public function iProvideALastNameOf($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @Given I provide an email of :email
     */
    public function iProvideAnEmailOf($email)
    {
        $this->email = $email;
    }

    /**
     * @Then an authorization header should be present
     */
    public function anAuthorizationHeaderShouldBePresent()
    {
        $request = $this->getRequestFromHistory();
        $headerLine = $request->getHeaderLine('Authorization');
        Assert::assertContains('Bearer ', $headerLine);
    }
}
