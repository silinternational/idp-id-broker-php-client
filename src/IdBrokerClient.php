<?php
namespace Sil\Idp\IdBroker\Client;

use Exception;
use GuzzleHttp\Command\Result;

/**
 * IdP ID Broker API client implemented with Guzzle.
 */
class IdBrokerClient extends BaseClient
{
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
        
        if (empty($accessToken)) {
            throw new \InvalidArgumentException(
                'Please provide an access token for the ID Broker.',
                1494531108
            );
        }
        
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
     * @return array An array with a sub-array about each user.
     */
    public function listUsers($fields = null)
    {
        $config = [];
        if ($fields !== null) {
            $config['fields'] = join(',', $fields);
        }
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
}
