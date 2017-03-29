<?php
namespace Sil\Idp\IdBroker\Client;

use GuzzleHttp\Command\Result;
use Exception;

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
        // Create the client (applying some defaults).
        parent::__construct(array_replace_recursive([
            'description_path' => \realpath(
                __DIR__ . '/descriptions/id-broker-api.php'
            ),
            'description_override' => [
                'baseUri' => $baseUri,
            ],
            'access_token' => $accessToken,
        ], $config));
    }
    
    public function authenticate(array $config = [])
    {
        $result = $this->authenticateInternal($config);
        $statusCode = (int)$result['statusCode'];
        
        if ($statusCode === 200) {
            return $this->getResultWithoutStatusCode($result);
        } elseif ($statusCode === 422) {
            return null;
        }
        
        throw new Exception(
            $result['message'] ?? 'Unknown error: ' . $statusCode,
            1490802360
        );
    }
    
    public function createUser(array $config = [])
    {
        $result = $this->createUserInternal($config);
        $statusCode = (int)$result['statusCode'];
        
        if ($statusCode === 200) {
            return $this->getResultWithoutStatusCode($result);
        }
        
        throw new Exception(
            $result['message'] ?? 'Unknown error: ' . $statusCode,
            1490802526
        );
    }
    
    public function deactivateUser(array $config = [])
    {
        $result = $this->deactivateUserInternal($config);
        $statusCode = (int)$result['statusCode'];
        
        if ($statusCode === 200) {
            return $this->getResultWithoutStatusCode($result);
        }
        
        throw new Exception(
            $result['message'] ?? 'Unknown error: ' . $statusCode,
            1490808523
        );
    }
    
    protected function getResultWithoutStatusCode($result)
    {
        unset($result['statusCode']);
        return $result;
    }
    
    public function getUser(array $config = [])
    {
        $result = $this->getUserInternal($config);
        $statusCode = (int)$result['statusCode'];
        
        if ($statusCode === 200) {
            return $this->getResultWithoutStatusCode($result);
        } elseif ($statusCode === 204) {
            return null;
        }
        
        throw new Exception(
            $result['message'] ?? 'Unknown error: ' . $statusCode,
            1490808555
        );
    }
    
    /**
     * 
     * @param array $config
     * @return Result
     */
    public function listUsers(array $config = [])
    {
        $result = $this->listUsersInternal($config);
        $statusCode = (int)$result['statusCode'];
        
        if ($statusCode === 200) {
            return $this->getResultWithoutStatusCode($result);
        }
        
        throw new Exception(
            $result['message'] ?? 'Unknown error: ' . $statusCode,
            1490808715
        );
    }
    
    public function setPassword(array $config = [])
    {
        $result = $this->setPasswordInternal($config);
        $statusCode = (int)$result['statusCode'];
        
        if ($statusCode === 200) {
            return $this->getResultWithoutStatusCode($result);
        }
        
        throw new Exception(
            $result['message'] ?? 'Unknown error: ' . $statusCode,
            1490808839
        );
    }
    
    public function updateUser(array $config = [])
    {
        $result = $this->updateUserInternal($config);
        $statusCode = (int)$result['statusCode'];
        
        if ($statusCode === 200) {
            return $this->getResultWithoutStatusCode($result);
        }
        
        throw new Exception(
            $result['message'] ?? 'Unknown error: ' . $statusCode,
            1490808841
        );
    }
}
