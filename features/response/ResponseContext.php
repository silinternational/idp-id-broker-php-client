<?php
namespace Sil\Idp\IdBroker\Client\features\response;

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Behat\Context\Context;
use Exception;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Assert;
use Sil\Idp\IdBroker\Client\exceptions\MfaRateLimitException;
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
    private $exceptionThrown = null;
    
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
            IdBrokerClient::ASSERT_VALID_BROKER_IP_CONFIG => false,
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
     * @When I call getSiteStatus
     */
    public function iCallGetsitestatus()
    {
        try {
            $this->getIdBrokerClient()->getSiteStatus();
        } catch (Exception $e) {
            $this->exceptionThrown = $e;
        }
    }

    /**
     * @When I call authenticate with the necessary data
     */
    public function iCallAuthenticateWithTheNecessaryData()
    {
        try {
            $this->result = $this->getIdBrokerClient()->authenticate(
                'john_smith',
                'dummy password'
            );
        } catch (Exception $e) {
            $this->exceptionThrown = $e;
        }
    }

    /**
     * @When I call authenticateNewUser with the necessary data
     */
    public function iCallAuthenticateNewUserWithTheNecessaryData()
    {
        try {
            $this->result = $this->getIdBrokerClient()->authenticateNewUser('xyz789');
        } catch (Exception $e) {
            $this->exceptionThrown = $e;
        }
    }

    /**
     * @Then the result should NOT contain user information
     */
    public function theResultShouldNotContainUserInformation()
    {
        if (is_array($this->result)) {
            foreach ($this->userInfoFields as $fieldName) {
                Assert::assertArrayNotHasKey($fieldName, $this->result);
            }
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
        try {
            $this->result = $this->getIdBrokerClient()->getUser('123245');
        } catch (Exception $e) {
            $this->exceptionThrown = $e;
        }
    }

    /**
     * @When I call listUsers with the necessary data
     */
    public function iCallListusersWithTheNecessaryData()
    {
        try {
            $this->result = $this->getIdBrokerClient()->listUsers();
        } catch (Exception $e) {
            $this->exceptionThrown = $e;
        }
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
        if (is_array($this->result)) {
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
    }

    /**
     * @When I call createUser with the necessary data
     */
    public function iCallCreateuserWithTheNecessaryData()
    {
        try {
            $this->result = $this->getIdBrokerClient()->createUser([
                'employee_id' => '12345',
                'first_name' => 'John',
                'last_name' => 'Smith',
                'username' => 'john_smith',
                'email' => 'john_smith@example.com',
            ]);
        } catch (Exception $e) {
            $this->exceptionThrown = $e;
        }
    }

    /**
     * @When I call updateUser with the necessary data
     */
    public function iCallUpdateuserWithTheNecessaryData()
    {
        try {
            $this->result = $this->getIdBrokerClient()->updateUser([
                'employee_id' => '12345',
                'first_name' => 'John',
            ]);
        } catch (Exception $e) {
            $this->exceptionThrown = $e;
        }
    }

    /**
     * @When I call setPassword with the necessary data
     */
    public function iCallSetpasswordWithTheNecessaryData()
    {
        try {
            $this->result = $this->getIdBrokerClient()->setPassword(
                '12345',
                'correcthorsebatterystaple'
            );
        } catch (Exception $e) {
            $this->exceptionThrown = $e;
        }
    }

    /**
     * @Then an exception should NOT have been thrown
     */
    public function anExceptionShouldNotHaveBeenThrown()
    {
        Assert::assertNull($this->exceptionThrown);
    }

    /**
     * @Then an exception SHOULD have been thrown
     */
    public function anExceptionShouldHaveBeenThrown()
    {
        Assert::assertInstanceOf(Exception::class, $this->exceptionThrown);
    }

    /**
     * @Then the result should be null
     */
    public function theResultShouldBeNull()
    {
        Assert::assertNull($this->result);
    }

    /**
     * @Then the result should be an array
     */
    public function theResultShouldBeAnArray()
    {
        Assert::assertInternalType('array', $this->result);
    }

    /**
     * @When I call mfaVerify with the necessary data
     */
    public function iCallMfaverifyWithTheNecessaryData()
    {
        try {
            $this->result = $this->getIdBrokerClient()->mfaVerify(
                '123',
                '111111',
                'dummy-mfa-submission'
            );
        } catch (Exception $e) {
            $this->exceptionThrown = $e;
        }
    }

    /**
     * @When an MFA rate-limit exception SHOULD have been thrown
     */
    public function anMfaRateLimitExceptionShouldHaveBeenThrown()
    {
        Assert::assertInstanceOf(
            MfaRateLimitException::class,
            $this->exceptionThrown
        );
    }

    /**
     * @Then the result should be true
     */
    public function theResultShouldBeTrue()
    {
        Assert::assertSame($this->result, true);
    }

    /**
     * @Then the result should be false
     */
    public function theResultShouldBeFalse()
    {
        Assert::assertSame($this->result, false);
    }

    /**
     * @When I call createMethod with the necessary data
     */
    public function iCallCreatemethodWithTheNecessaryData()
    {
        $this->result = $this->getIdBrokerClient()->createMethod(
            '123',
            '111111'
        );
    }

    /**
     * @When I call verifyMethod with the necessary data
     */
    public function iCallVerifyMethodWithTheNecessaryData()
    {
        try {
            $this->result = $this->getIdBrokerClient()->verifyMethod(
                '123',
                '111111',
                'dummy-method-submission'
            );
        } catch (Exception $e) {
            $this->exceptionThrown = $e;
        }
    }

    /**
     * @When I call resendMethod with the necessary data
     */
    public function iCallResendMethodWithTheNecessaryData()
    {
        try {
            $this->result = $this->getIdBrokerClient()->resendMethod(
                '123',
                '111111'
            );
        } catch (Exception $e) {
            $this->exceptionThrown = $e;
        }
    }
}
