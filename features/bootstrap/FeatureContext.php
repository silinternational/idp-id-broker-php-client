<?php

use Behat\Behat\Context\Context;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\Assert;
use Sil\Idp\IdBroker\Client\AuthenticationClient;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    private $baseUri;
    private $password;
    private $username;
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
    
    protected function getHttpClientForTests()
    {
        $mockHandler = new MockHandler([
            new Response(),
        ]);
        $handlerStack = HandlerStack::create($mockHandler);

        // Add a history middleware to the handler stack.
        $historyMiddleware = Middleware::history($this->requestHistory);
        $handlerStack->push($historyMiddleware);
        
        return new HttpClient(['handler' => $handlerStack]);
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
     * @Given I provide a username of :username
     */
    public function iProvideAUsernameOf($username)
    {
        $this->username = $username;
    }

    /**
     * @Given I provide a password of :password
     */
    public function iProvideAPasswordOf($password)
    {
        $this->password = $password;
    }

    /**
     * @When I call authenticate
     */
    public function iCallAuthenticate()
    {
        $authenticationClient = new AuthenticationClient([
            'description_override' => [
                'baseUri' => $this->baseUri,
            ],
            'http_client' => $this->getHttpClientForTests(),
        ]);
        $authenticationClient->authenticate([
            'username' => $this->username,
            'password' => $this->password,
        ]);
    }

    /**
     * @Then the url should be :expectedUri
     */
    public function theUrlShouldBe($expectedUri)
    {
        $request = $this->getRequestFromHistory();
        $actualUri = (string)$request->getUri();
        Assert::assertSame($expectedUri, $actualUri, sprintf(
            'Expected %s, not %s.',
            var_export($expectedUri, true),
            var_export($actualUri, true)
        ));
    }

    /**
     * @Then the body should be :expectedBodyText
     */
    public function theBodyShouldBe($expectedBodyText)
    {
        $request = $this->getRequestFromHistory();
        $actualBodyText = (string)$request->getBody();
        Assert::assertSame($expectedBodyText, $actualBodyText, sprintf(
            'Expected %s, not %s.',
            var_export($expectedBodyText, true),
            var_export($actualBodyText, true)
        ));
    }
}
