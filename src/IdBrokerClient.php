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
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        // Apply some defaults.
        $config += [
            'description_path' => \realpath(
                __DIR__ . '/descriptions/id-broker-api.php'
            ),
        ];

        // Create the client.
        parent::__construct($config);
    }
}
