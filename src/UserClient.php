<?php
namespace Sil\Idp\IdBroker\Client;

/**
 * IdP ID Broker user API client implemented with Guzzle.
 *
 * @method array create(array $config = [])
 * @method array deactivate(array $config = [])
 * @method array find(array $config = [])
 * @method array get(array $config = [])
 * @method array list(array $config = [])
 * @method array update(array $config = [])
 */
class UserClient extends BaseClient
{
    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        // Apply some defaults.
        $config += [
            'description_path' => \realpath(__DIR__ . '/descriptions/user.php'),
        ];

        // Create the client.
        parent::__construct($config);
    }
}
