<?php
namespace Sil\Idp\IdBroker\Client;

/**
 * IdP ID Broker password API client implemented with Guzzle.
 *
 * @method array set(array $config = [])
 */
class PasswordClient extends BaseClient
{
    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        // Apply some defaults.
        $config += [
            'description_path' => \realpath(__DIR__ . '/descriptions/password.php'),
        ];

        // Create the client.
        parent::__construct($config);
    }
}
