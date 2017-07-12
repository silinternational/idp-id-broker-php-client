<?php
namespace Sil\Idp\IdBroker\Client;

use Exception;
use GuzzleHttp\Command\Result;
use IPBlock;

/**
 * IdP ID Broker API client implemented with Guzzle.
 */
class IdBrokerClient extends BaseClient
{
    /**
     * The key for the constructor's config parameter that refers
     * to the trusted IP ranges.
     */
    const TRUSTED_IPS_CONFIG = 'trusted_ip_ranges';
    const ASSERT_VALID_BROKER_IP_CONFIG = 'assert_valid_broker_ip';

    /**
     * The list of trusted IP address ranges (aka. blocks).
     *
     * @var IPBlock[]
     */
    private $trustedIpRanges = [];

    private $assertValidBrokerIp = true;

    private $idBrokerUri;

    /**
     * Constructor.
     *
     * @param string $baseUri - The base of the API's URL.
     *     Example: 'https://api.example.com/'.
     * @param string $accessToken - Your authorization access (bearer) token.
     * @param array $config - Any other configuration settings.
     */
    public function __construct(
        string $baseUri,
        string $accessToken,
        array $config = []
    ) {
        if (empty($baseUri)) {
            throw new \InvalidArgumentException(
                'Please provide a base URI for the ID Broker.',
                1494531101
            );
        }

        $this->idBrokerUri = $baseUri;
        
        if (empty($accessToken)) {
            throw new \InvalidArgumentException(
                'Please provide an access token for the ID Broker.',
                1494531108
            );
        }

        $this->initializeConfig($config);

        // Create the client (applying some defaults).
        parent::__construct(array_replace_recursive([
            'description_path' => \realpath(
                __DIR__ . '/descriptions/id-broker-api.php'
            ),
            'description_override' => [
                'baseUri' => $baseUri,
            ],
            'access_token' => $accessToken,
            'http_client_options' => [
                'timeout' => 30,
            ],
        ], $config));
    }

    /*
     * Validates the config values for ASSERT_VALID_BROKER_IP_CONFIG and
     *   ASSERT_VALID_BROKER_IP_CONFIG
     * Uses them to set $this->assertValidBrokerIp and $this->trustedIpRanges
     *
     * @param array the config values for the client
     *
     * @return null
     * @throws \InvalidArgumentException
     */
    private function initializeConfig($config) {

        if ( isset($config[self::ASSERT_VALID_BROKER_IP_CONFIG])) {
            $this->assertValidBrokerIp = $config[self::ASSERT_VALID_BROKER_IP_CONFIG];
        }

        /*
         *  If we should validate the Broker IP but there aren't
         *  any trusted IPs, throw an exception
         */
        if (($this->assertValidBrokerIp) &&
            empty($config[self::TRUSTED_IPS_CONFIG])) {
            throw new \InvalidArgumentException(
                'The config entry for ' . self::TRUSTED_IPS_CONFIG .
                ' must be set (as an array) when ' .
                self::ASSERT_VALID_BROKER_IP_CONFIG .
                ' is not set or is set to True.',
                1494531150
            );
        }

        if ( ! $this->assertValidBrokerIp) {
            return;
        }

        /*
         * At this point, we need to validate the Broker Ip and we know
         * that the TRUSTED_IPS_CONFIG is not empty
         */
        $newTrustedIpRanges = $config[self::TRUSTED_IPS_CONFIG];
        if ( ! is_array($newTrustedIpRanges)) {
            throw new \InvalidArgumentException(
                'The config entry for ' . self::TRUSTED_IPS_CONFIG .
                ' must be an array.',
                1494531200
            );
        }

        foreach ($newTrustedIpRanges as $nextIpRange) {
            $ipBlock = IPBlock::create($nextIpRange);
            $this->trustedIpRanges[] = $ipBlock;
        }

        $this->assertTrustedBrokerIp();
    }

    /**
     * Attempt to authenticate using the given credentials, getting back
     * information about the authenticated user (if the credentials were
     * acceptable) or null (if unacceptable).
     *
     * @param string $username The username.
     * @param string $password The password (in plaintext).
     * @return array|null An array of user information (if valid), or null.
     * @throws Exception
     */
    public function authenticate(string $username, string $password)
    {
        $result = $this->authenticateInternal([
            'username' => $username,
            'password' => $password,
        ]);
        $statusCode = (int)$result['statusCode'];
        
        if ($statusCode === 200) {
            return $this->getResultAsArrayWithoutStatusCode($result);
        } elseif ($statusCode === 400) {
            return null;
        }
        
        $this->reportUnexpectedResponse($result, 1490802360);
    }
    
    /**
     * Create a user with the given information.
     *
     * @param array $config An array key/value pairs of attributes for the new
     *     user.
     * @return array An array of information about the new user.
     * @throws Exception
     */
    public function createUser(array $config = [])
    {
        $result = $this->createUserInternal($config);
        $statusCode = (int)$result['statusCode'];
        
        if ($statusCode === 200) {
            return $this->getResultAsArrayWithoutStatusCode($result);
        }
        
        $this->reportUnexpectedResponse($result, 1490802526);
    }
    
    /**
     * Deactivate a user.
     *
     * @param string $employeeId The Employee ID of the user to deactivate.
     * @throws Exception
     */
    public function deactivateUser(string $employeeId)
    {
        $result = $this->deactivateUserInternal([
            'employee_id' => $employeeId,
            'active' => 'no',
        ]);
        $statusCode = (int)$result['statusCode'];
        
        if ($statusCode !== 200) {
            $this->reportUnexpectedResponse($result, 1490808523);
        }
    }
    
    /**
     * Convert the result of the Guzzle call to an array without a status code.
     *
     * @param Result $result The result of a Guzzle call.
     * @return array
     */
    protected function getResultAsArrayWithoutStatusCode($result)
    {
        unset($result['statusCode']);
        return $result->toArray();
    }

    /**
     * Ping the /site/status url
     *
     * @return string "OK".
     * @throws Exception
     */
    public function getSiteStatus()
    {
        $result = $this->getSiteStatusInternal();
        $statusCode = (int)$result['statusCode'];

        if (($statusCode >= 200) && ($statusCode < 300)) {
            return 'OK';
        }

        $this->reportUnexpectedResponse($result, 1490806100);
    }
    
    /**
     * Get information about the specified user.
     *
     * @param string $employeeId The Employee ID of the desired user.
     * @return array|null An array of information about the specified user, or
     *     null if no such user was found.
     * @throws Exception
     */
    public function getUser(string $employeeId)
    {
        $result = $this->getUserInternal([
            'employee_id' => $employeeId,
        ]);
        $statusCode = (int)$result['statusCode'];
        
        if ($statusCode === 200) {
            return $this->getResultAsArrayWithoutStatusCode($result);
        } elseif ($statusCode === 204) {
            return null;
        }
        
        $this->reportUnexpectedResponse($result, 1490808555);
    }
    
    /**
     * Get a list of all users.
     *
     * @param array|null $fields (Optional:) The list of fields desired about
     *     each user in the result.
     * @param  array|null $search (Optional:) An array of fields to search on,
     *     example ['username' => 'billy']
     * @return array An array with a sub-array about each user.
     */
    public function listUsers($fields = null, $search = [])
    {
        $config = [];
        if ($fields !== null) {
            $config['fields'] = join(',', $fields);
        }
        $config = array_merge($config, $search);
        $result = $this->listUsersInternal($config);
        $statusCode = (int)$result['statusCode'];
        
        if ($statusCode === 200) {
            return $this->getResultAsArrayWithoutStatusCode($result);
        }
        
        $this->reportUnexpectedResponse($result, 1490808715);
    }
    
    /**
     * Set the password for the specified user.
     *
     * @param string $employeeId The Employee ID of the user whose password we
     *     are trying to set.
     * @param string $password The desired (new) password, in plaintext.
     * @throws Exception
     */
    public function setPassword(string $employeeId, string $password)
    {
        $result = $this->setPasswordInternal([
            'employee_id' => $employeeId,
            'password' => $password,
        ]);
        $statusCode = (int)$result['statusCode'];
        
        if ($statusCode !== 200) {
            $this->reportUnexpectedResponse($result, 1490808839);
        }
    }
    
    protected function reportUnexpectedResponse($response, $uniqueErrorCode)
    {
        throw new Exception(
            sprintf(
                'Unexpected response: %s',
                var_export($response, true)
            ),
            $uniqueErrorCode
        );
    }
    
    /**
     * Update the specified user with the given information.
     *
     * @param array $config An array key/value pairs of attributes for the user.
     *     Must include at least an 'employee_id' entry.
     * @return array An array of information about the updated user.
     * @throws Exception
     */
    public function updateUser(array $config = [])
    {
        $result = $this->updateUserInternal($config);
        $statusCode = (int)$result['statusCode'];
        
        if ($statusCode === 200) {
            return $this->getResultAsArrayWithoutStatusCode($result);
        }
        
        $this->reportUnexpectedResponse($result, 1490808841);
    }

    /**
     * Determine whether any of the Id-broker's IPs are not in the
     * trusted ranges
     *
     * @throws Exception
     */
    private function assertTrustedBrokerIp() {
        $baseHost = parse_url($this->idBrokerUri, PHP_URL_HOST);
        $idBrokerIp = gethostbyname(
            $baseHost
        );

        if ( ! $this->isTrustedIpAddress($idBrokerIp)) {
            throw new Exception(
                'The Id Broker has an IP that is not trusted ... ' . $idBrokerIp,
                1494531300
            );
        }
    }

    /**
     * Determine whether the id-broker's IP address is in a trusted range.
     *
     * @param string $ipAddress The IP address in question.
     * @return bool
     */
    private function isTrustedIpAddress($ipAddress)
    {
        foreach ($this->trustedIpRanges as $trustedIpBlock) {
            if ($trustedIpBlock->containsIP($ipAddress)) {
                return true;
            }
        }

        return false;
    }
}
