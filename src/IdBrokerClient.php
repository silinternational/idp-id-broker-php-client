<?php
namespace Sil\Idp\IdBroker\Client;

use GuzzleHttp\Command\Result;

/**
 * IdP ID Broker API client implemented with Guzzle.
 *
 * @method Result activateUser(array $config = [])
 * @method Result authenticate(array $config = [])
 * @method Result createUser(array $config = [])
 * @method Result deactivateUser(array $config = [])
 * @method Result findUser(array $config = [])
 * @method Result getUser(array $config = [])
 * @method Result listUsers(array $config = [])
 * @method Result setPassword(array $config = [])
 * @method Result updateUser(array $config = [])
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
}
