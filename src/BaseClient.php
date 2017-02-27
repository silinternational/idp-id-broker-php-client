<?php
namespace Sil\Idp\IdBroker\Client;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Command\Guzzle\GuzzleClient;
use GuzzleHttp\Command\Guzzle\Description;

class BaseClient extends GuzzleClient
{
    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        // Apply some defaults.
        $config += [
            /** @todo Changed... find equivalent. */
            //'max_retries' => 3,
        ];

        // Create the client.
        parent::__construct(
            $this->getHttpClientFromConfig($config),
            $this->getDescriptionFromConfig($config),
            null,
            null,
            null,
            $config
        );

        // Ensure that the credentials are set.
        $this->applyCredentials($config);
    }

    private function getHttpClientFromConfig(array $config)
    {
        // If a client was provided, return it.
        if (isset($config['http_client'])) {
            return $config['http_client'];
        }

        // Create a Guzzle HttpClient.
        $clientOptions = isset($config['http_client_options'])
            ? $config['http_client_options']
            : [];
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

    private function getDescriptionFromConfig(array $config)
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

        return new Description($data);
    }

    private function applyCredentials(array $config)
    {
        /** @todo Ensure that the credentials have been provided. */
        
        //if (!isset($config['api_key'])) {
        //    throw new \InvalidArgumentException(
        //        'You must provide an api_key.'
        //    );
        //}
        //if (!isset($config['api_secret'])) {
        //    throw new \InvalidArgumentException(
        //        'You must provide an api_secret.'
        //    );
        //}
        //
        //// Set the credentials in default variables so that we don't have to
        //// pass them to every method individually.
        //$this->setConfig(
        //    'defaults/api_key',
        //    $config['api_key']
        //);
        //$this->setConfig(
        //    'defaults/api_secret',
        //    $config['api_secret']
        //);
    }
}
