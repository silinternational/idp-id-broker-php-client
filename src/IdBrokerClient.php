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
    private $trustedIpRanges = [ ];

    private $assertValidBrokerIp = true;

    private $idBrokerUri;

    /**
     * Constructor.
     *
     * @param string $baseUri - The base of the API's URL.
     *     Example: 'https://api.example.com/'.
     * @param string $accessToken - Your authorization access (bearer) token.
     * @param array $config - Any other configuration settings.
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    public function __construct(
        string $baseUri,
        string $accessToken,
        array $config = [ ]
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

    /**
     * Validates the config values for ASSERT_VALID_BROKER_IP_CONFIG and
     *   TRUSTED_IPS_CONFIG.
     * Uses them to set $this->assertValidBrokerIp and $this->trustedIpRanges
     *
     * @param array the config values for the client
     *
     * @return null
     * @throws \InvalidArgumentException
     * @throws \Exception if assertValidBrokerIp is true and idBrokerUri is invalid, unresolvable, or untrusted
     */
    private function initializeConfig($config)
    {

        if (isset($config[ self::ASSERT_VALID_BROKER_IP_CONFIG ])) {
            $this->assertValidBrokerIp = $config[ self::ASSERT_VALID_BROKER_IP_CONFIG ];
        }

        // If we don't need to validate the Broker Ip, we're done here
        if ( ! $this->assertValidBrokerIp) {
            return;
        }

        /*
         *  If we should validate the Broker IP but there aren't
         *  any trusted IPs, throw an exception
         */
        if (empty($config[ self::TRUSTED_IPS_CONFIG ])) {
            throw new \InvalidArgumentException(
                'The config entry for ' . self::TRUSTED_IPS_CONFIG .
                ' must be set (as an array) when ' .
                self::ASSERT_VALID_BROKER_IP_CONFIG .
                ' is not set or is set to True.',
                1494531150
            );
        }

        /*
         * At this point, we need to validate the Broker Ip and we know
         * that the TRUSTED_IPS_CONFIG is not empty
         */
        $newTrustedIpRanges = $config[ self::TRUSTED_IPS_CONFIG ];
        if ( ! is_array($newTrustedIpRanges)) {
            throw new \InvalidArgumentException(
                'The config entry for ' . self::TRUSTED_IPS_CONFIG .
                ' must be an array, not ' . var_export($newTrustedIpRanges, true),
                1494531200
            );
        }

        foreach ($newTrustedIpRanges as $nextIpRange) {
            $ipBlock = IPBlock::create($nextIpRange);
            $this->trustedIpRanges[ ] = $ipBlock;
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
     * @throws ServiceException
     */
    public function authenticate(string $username, string $password)
    {
        $result = $this->authenticateInternal([
            'username' => $username,
            'password' => $password,
        ]);
        $statusCode = (int)$result[ 'statusCode' ];
        
        if ($statusCode === 200) {
            return $this->getResultAsArrayWithoutStatusCode($result);
        } elseif ($statusCode === 400) {
            return null;
        }
        
        $this->reportUnexpectedResponse($result, 1490802360);
    }

    /**
     * Attempt to authenticate using a new user invite code
     *
     * @param string $invite The new user invite code.
     * @return array|null An array of user information (if valid), or null.
     * @throws ServiceException
     */
    public function authenticateNewUser(string $invite)
    {
        $result = $this->authenticateNewUserInternal([
            'invite' => $invite,
        ]);
        $statusCode = (int)$result[ 'statusCode' ];

        if ($statusCode === 200) {
            return $this->getResultAsArrayWithoutStatusCode($result);
        } elseif ($statusCode === 400) {
            return null;
        }

        $this->reportUnexpectedResponse($result, 1544549972);
    }

    /**
     * Create a user with the given information.
     *
     * @param array $config An array key/value pairs of attributes for the new
     *     user.
     * @return array An array of information about the new user.
     * @throws ServiceException
     */
    public function createUser(array $config = [ ])
    {
        $result = $this->createUserInternal($config);
        $statusCode = (int)$result[ 'statusCode' ];
        
        if ($statusCode === 200) {
            return $this->getResultAsArrayWithoutStatusCode($result);
        }
        
        $this->reportUnexpectedResponse($result, 1490802526);
    }
    
    /**
     * Deactivate a user.
     *
     * @param string $employeeId The Employee ID of the user to deactivate.
     * @throws ServiceException
     */
    public function deactivateUser(string $employeeId)
    {
        $result = $this->deactivateUserInternal([
            'employee_id' => $employeeId,
            'active' => 'no',
        ]);
        $statusCode = (int)$result[ 'statusCode' ];
        
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
        unset($result[ 'statusCode' ]);
        return $result->toArray();
    }

    /**
     * Ping the /site/status url
     *
     * @return string "OK".
     * @throws ServiceException
     */
    public function getSiteStatus()
    {
        $result = $this->getSiteStatusInternal();
        $statusCode = (int)$result[ 'statusCode' ];

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
     * @throws ServiceException
     */
    public function getUser(string $employeeId)
    {
        $result = $this->getUserInternal([
            'employee_id' => $employeeId,
        ]);
        $statusCode = (int)$result[ 'statusCode' ];
        
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
     * @throws ServiceException
     */
    public function listUsers($fields = null, $search = [ ])
    {
        $config = [ ];
        if ($fields !== null) {
            $config[ 'fields' ] = join(',', $fields);
        }
        $config = array_merge($config, $search);
        $result = $this->listUsersInternal($config);
        $statusCode = (int)$result[ 'statusCode' ];
        
        if ($statusCode === 200) {
            return $this->getResultAsArrayWithoutStatusCode($result);
        }
        
        $this->reportUnexpectedResponse($result, 1490808715);
    }

    /**
     * Create a new MFA configuration
     * @param string $employee_id
     * @param string $type
     * @param string $label
     * @return array|null
     * @throws ServiceException
     */
    public function mfaCreate($employee_id, $type, $label = null)
    {
        $result = $this->mfaCreateInternal([
            'employee_id' => $employee_id,
            'type' => $type,
            'label' => $label,
        ]);
        $statusCode = (int)$result[ 'statusCode' ];

        if ($statusCode === 200) {
            return $this->getResultAsArrayWithoutStatusCode($result);
        }

        $this->reportUnexpectedResponse($result, 1506710701);
    }

    /**
     * Delete an MFA configuration
     * @param int $id
     * @param string $employeeId
     * @return null
     * @throws ServiceException
     */
    public function mfaDelete($id, $employeeId)
    {
        $result = $this->mfaDeleteInternal([
            'id' => $id,
            'employee_id' => $employeeId,
        ]);
        $statusCode = (int)$result[ 'statusCode' ];

        if ($statusCode === 204) {
            return null;
        }

        $this->reportUnexpectedResponse($result, 1506710702);
    }

    /**
     * Get a list of MFA configurations for given user
     * @param string $employee_id
     * @return array
     * @throws ServiceException
     */
    public function mfaList($employee_id)
    {
        $result = $this->mfaListInternal([
            'employee_id' => $employee_id,
        ]);
        $statusCode = (int)$result[ 'statusCode' ];

        if ($statusCode === 200) {
            return $this->getResultAsArrayWithoutStatusCode($result);
        }

        $this->reportUnexpectedResponse($result, 1506710703);
    }

    /**
     * Update an MFA configuration
     * @param int $id
     * @param string $employeeId
     * @param string $label
     * @return array
     * @throws ServiceException
     */
    public function mfaUpdate($id, $employeeId, $label)
    {
        $result = $this->mfaUpdateInternal([
            'id' => $id,
            'employee_id' => $employeeId,
            'label' => $label,
        ]);
        $statusCode = (int)$result[ 'statusCode' ];

        if ($statusCode === 200) {
            return $this->getResultAsArrayWithoutStatusCode($result);
        }

        $this->reportUnexpectedResponse($result, 1543879805);
    }

    /**
     * Verify an MFA value
     * @param string $id The MFA ID.
     * @param string $employeeId The Employee ID of the user with that MFA.
     * @param string $value The MFA value being verified.
     * @return bool|array
     * @throws MfaRateLimitException
     * @throws ServiceException
     */
    public function mfaVerify($id, $employeeId, $value)
    {
        $result = $this->mfaVerifyInternal([
            'id' => $id,
            'employee_id' => $employeeId,
            'value' => $value,
        ]);
        $statusCode = (int)$result[ 'statusCode' ];

        /*
         * Accept a 204 for compatibility with earlier ID Broker versions
         */
        if ($statusCode === 204) {
            return true;
        }

        if ($statusCode === 200) {
            return $this->getResultAsArrayWithoutStatusCode($result);
        }

        $this->reportUnexpectedResponse($result, 1506710704);
    }

    /**
     * Create a new recovery method
     * @param string $employee_id
     * @param string $value
     * @param string $created If specified, indicates the record is to be created pre-verified.
     * @return String[]
     * @throws ServiceException
     */
    public function createMethod($employee_id, $value, $created = '')
    {
        $params = compact('employee_id', 'value');
        if (! empty($created)) {
            $params['created'] = $created;
        }

        $result = $this->createMethodInternal($params);
        $statusCode = (int)$result[ 'statusCode' ];

        if ($statusCode === 200) {
            return $this->getResultAsArrayWithoutStatusCode($result);
        }

        $this->reportUnexpectedResponse($result, 1541006274);
    }

    /**
     * Delete a recovery method
     * @param int $uid
     * @param int $employee_id
     * @return null
     * @throws ServiceException
     */
    public function deleteMethod($uid, $employee_id)
    {
        $result = $this->deleteMethodInternal(compact('uid', 'employee_id'));
        $statusCode = (int)$result[ 'statusCode' ];

        if ($statusCode === 204 || $statusCode === 200) {
            return null;
        }

        $this->reportUnexpectedResponse($result, 1541006315);
    }

    /**
     * View a single recovery method
     * @param int $uid
     * @param int $employee_id
     * @return String[]
     * @throws ServiceException
     */
    public function getMethod($uid, $employee_id)
    {
        $result = $this->getMethodInternal(compact('uid', 'employee_id'));
        $statusCode = (int)$result[ 'statusCode' ];

        if ($statusCode === 200) {
            return $this->getResultAsArrayWithoutStatusCode($result);
        }

        $this->reportUnexpectedResponse($result, 1541006615);
    }

    /**
     * Get a list of recovery methods for given user
     * @param String $employee_id
     * @return String[]
     * @throws ServiceException
     */
    public function listMethod($employee_id)
    {
        $result = $this->listMethodInternal(compact('employee_id'));
        $statusCode = (int)$result[ 'statusCode' ];

        if ($statusCode === 200) {
            return $this->getResultAsArrayWithoutStatusCode($result);
        }

        $this->reportUnexpectedResponse($result, 1541006346);
    }

    /**
     * Verify a recovery method
     * @param string $uid The Method UID.
     * @param string $employee_id The Employee ID of the user with that Method.
     * @param string code The recovery method verification code
     * @return String[]
     * @throws ServiceException
     */
    public function verifyMethod($uid, $employee_id, $code)
    {
        $result = $this->verifyMethodInternal(compact('uid', 'employee_id', 'code'));
        $statusCode = (int)$result[ 'statusCode' ];

        if ($statusCode === 200) {
            return $this->getResultAsArrayWithoutStatusCode($result);
        }

        $this->reportUnexpectedResponse($result, 1541006448);
    }

    /**
     * Resend a recovery method verification message
     * @param string $uid The Method UID.
     * @param string $employee_id The Employee ID of the user with that Method.
     * @return bool
     * @throws ServiceException
     */
    public function resendMethod($uid, $employee_id)
    {
        $result = $this->resendMethodInternal(compact('uid', 'employee_id'));
        $statusCode = (int)$result[ 'statusCode' ];

        if ($statusCode === 204 || $statusCode === 200) {
            return true;
        }

        $this->reportUnexpectedResponse($result, 1541006732);
    }

    /**
     * Set the password for the specified user.
     *
     * @param string $employeeId The Employee ID of the user whose password we
     *     are trying to set.
     * @param string $password The desired (new) password, in plaintext.
     *
     * @return array An array of password metadata
     * @throws ServiceException
     */
    public function setPassword(string $employeeId, string $password)
    {
        $result = $this->setPasswordInternal([
            'employee_id' => $employeeId,
            'password' => $password,
        ]);
        $statusCode = (int)$result[ 'statusCode' ];

        if ($statusCode === 200) {
            return $this->getResultAsArrayWithoutStatusCode($result);
        }

        $this->reportUnexpectedResponse($result, 1490808839);
    }

    /**
     * Validate a new password for a specified user, but do not save it.
     *
     * @param string $employeeId The Employee ID of the user for whom we
     *     are validating a new password.
     * @param string $password The desired password, in plaintext.
     *
     * @return bool
     * @throws ServiceException
     */
    public function assessPassword(string $employeeId, string $password)
    {
        $result = $this->assessPasswordInternal([
            'employee_id' => $employeeId,
            'password' => $password,
        ]);
        $statusCode = (int)$result[ 'statusCode' ];

        if ($statusCode >= 200 && $statusCode <= 299) {
            return true;
        }

        if ($statusCode >= 400 && $statusCode <= 499) {
            return false;
        }

        $this->reportUnexpectedResponse($result, 1554404870);
    }

    /**
     * @param \GuzzleHttp\Command\Result $response
     * @param int $uniqueErrorCode
     * @throws ServiceException
     */
    protected function reportUnexpectedResponse($response, $uniqueErrorCode)
    {
        throw new ServiceException(
            sprintf(
                'Unexpected response: %s',
                var_export($response, true)
            ),
            $uniqueErrorCode,
            (int)$response[ 'statusCode' ]
        );
    }

    /**
     * Update the specified user with the given information.
     *
     * @param array $config An array key/value pairs of attributes for the user.
     *     Must include at least an 'employee_id' entry.
     * @return array An array of information about the updated user.
     * @throws ServiceException
     */
    public function updateUser(array $config = [ ])
    {
        $result = $this->updateUserInternal($config);
        $statusCode = (int)$result[ 'statusCode' ];
        
        if ($statusCode === 200) {
            return $this->getResultAsArrayWithoutStatusCode($result);
        }
        
        $this->reportUnexpectedResponse($result, 1490808841);
    }

    /**
     * Determine whether any of the Id-broker's IPs are not in the
     * trusted ranges
     *
     * @throws Exception if idBrokerUri is invalid, unresolvable, or untrusted
     */
    private function assertTrustedBrokerIp()
    {
        $baseHost = parse_url($this->idBrokerUri, PHP_URL_HOST);
        $idBrokerIp = gethostbyname(
            $baseHost
        );

        if (! $this->isTrustedIpAddress($idBrokerIp)) {
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
