<?php
namespace Sil\Idp\IdBroker\Client;

/**
 * IdP ID Broker API client implemented with Guzzle.
 *
 * @method array authenticate(array $config = [])
 * @method array createUser(array $config = [])
 * @method array deactivateUser(array $config = [])
 * @method array findUsers(array $config = [])
 * @method array getUser(array $config = [])
 * @method array listUsers(array $config = [])
 * @method array setPassword(array $config = [])
 * @method array updateUser(array $config = [])
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
