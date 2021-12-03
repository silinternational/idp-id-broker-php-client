<?php
namespace Sil\Idp\IdBroker\Client;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Command\Guzzle\GuzzleClient;
use GuzzleHttp\Command\Guzzle\Description;

class BaseClient extends GuzzleClient
{
    /**
     * @param array $config
     * @throws \Exception
     */
    public function __construct(array $config = [])
    {
        // Ensure that the credentials have been provided.
        if ( ! isset($config['access_token'])) {
            throw new \InvalidArgumentException(
                'You must provide an Access Token.'
            );
        }
        
        // Apply some defaults.
        $mergedConfig = array_replace_recursive($config, [
            /** @todo Changed... find equivalent. */
            //'max_retries' => 3,
            
            'http_client_options' => [
                'http_errors' => false,
                'headers' => [
                    'Authorization' => 'Bearer ' . $config['access_token'],
                ],
            ],
        ]);
        
        // Create the client.
        parent::__construct(
            $this->getHttpClientFromConfig($mergedConfig),
            $this->getDescriptionFromConfig($mergedConfig),
            null,
            null,
            null,
            $mergedConfig
        );
    }

    /**
     * @param array $config
     * @return HttpClient
     */
    private function getHttpClientFromConfig(array $config): HttpClient
    {
        // If a client was provided, return it.
        if (isset($config['http_client'])) {
            return $config['http_client'];
        }
        
        // Create a Guzzle HttpClient.
        $clientOptions = $config['http_client_options'] ?? [];
        $client = new HttpClient($clientOptions);
        
        // Attach request retry logic.
        /** @todo Changed... find equivalent. */
//        $client->getEmitter()->attach(new RetrySubscriber([
//            'max' => $config['max_retries'],
//            'filter' => RetrySubscriber::createChainFilter([
//                RetrySubscriber::createStatusFilter(),
//                RetrySubscriber::createCurlFilter(),
//            ]),
//        ]));
        
        return $client;
    }

    /**
     * @param array $config
     * @return Description|mixed
     * @throws \Exception
     */
    private function getDescriptionFromConfig(array $config): Description
    {
        // If a description was provided, return it.
        if (isset($config['description'])) {
            return $config['description'];
        }
        
        // Load service description data.
        $data = is_readable($config['description_path'])
            ? include $config['description_path']
            : [];
        
        // Override description from local config if set
        if (isset($config['description_override'])) {
            $data = array_replace_recursive($data, $config['description_override']);
        }
        
        if ( ! isset($data['baseUri'])) {
            throw new \Exception('A baseUri is required.', 1488211973);
        }
        
        return new Description($data);
    }
}
