<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use GuzzleHttp\Client as HttpClient;
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
class FeatureContext implements Context
{
    private $baseUri;
    
    private $employeeId;
    private $firstName;
    private $lastName;
    private $username;
    private $password;
    private $email;
    
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
        $this->getIdBrokerClient()->authenticate([
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
     * @When I call activateUser
     */
    public function iCallActivateUser()
    {
        $this->getIdBrokerClient()->activateUser([
            'employee_id' => $this->employeeId,
        ]);
    }

    /**
     * @When I call createUser
     */
    public function iCallCreateUser()
    {
        $this->getIdBrokerClient()->createUser([
            'employee_id' => $this->employeeId,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'username' => $this->username,
            'email' => $this->email,
        ]);
    }

    /**
     * @Given I provide an employee_id of :employeeId
     */
    public function iProvideAnEmployeeIdOf($employeeId)
    {
        $this->employeeId = $employeeId;
    }

    /**
     * @When I call deactivateUser
     */
    public function iCallDeactivateUser()
    {
        $this->getIdBrokerClient()->deactivateUser([
            'employee_id' => $this->employeeId,
        ]);
    }

    /**
     * @When I call findUsers
     */
    public function iCallFindUsers()
    {
        $this->getIdBrokerClient()->findUsers([
            'username' => $this->username,
        ]);
    }

    /**
     * @When I call getUser
     */
    public function iCallGetUser()
    {
        $this->getIdBrokerClient()->getUser([
            'employee_id' => $this->employeeId,
        ]);
    }

    /**
     * @When I call listUsers
     */
    public function iCallListUsers()
    {
        $this->getIdBrokerClient()->listUsers();
    }

    /**
     * @When I call setPassword
     */
    public function iCallSetPassword()
    {
        $this->getIdBrokerClient()->setPassword([
            'employee_id' => $this->employeeId,
            'password' => $this->password,
        ]);
    }

    /**
     * @When I call updateUser
     */
    public function iCallUpdateUser()
    {
        $this->getIdBrokerClient()->updateUser([
            'employee_id' => $this->employeeId,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'username' => $this->username,
            'email' => $this->email,
        ]);
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
