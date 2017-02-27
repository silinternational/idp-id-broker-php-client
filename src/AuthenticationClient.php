<?php
namespace Sil\Idp\IdBroker\Client;

/**
 * IdP ID Broker user authentication API client implemented with Guzzle.
 *
 * @method array authenticate(array $config = [])
 */
class AuthenticationClient extends BaseClient
{
    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        // Apply some defaults.
        $config += [
            'description_path' => \realpath(__DIR__ . '/descriptions/authentication.php'),
        ];

        // Create the client.
        parent::__construct($config);
    }
}
