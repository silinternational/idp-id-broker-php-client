# idp-id-broker-php-client
PHP client to interact with our [IdP ID Broker](https://github.com/silinternational/idp-id-broker)'s API.

This client is built on top of 
[Guzzle](http://docs.guzzlephp.org/en/stable/), the PHP HTTP Client. 
Guzzle has a simple way to create API clients by describing the API in a 
Swagger-like format without the need to implement every method yourself. So 
adding support for more APIs is relatively simple.


## Install ##
Installation is simple with [Composer](https://getcomposer.org/):

    $ composer require silinternational/idp-id-broker-php-client


## Usage ##

Example:

```php
<?php

use Sil\Idp\IdBroker\Client\UserClient;

$userClient = new UserClient([
    // Authentication values...
]);

$users = $userClient->list();
```


## Tests ##

To run the unit tests for this, run `make test`.


## Guzzle Service Client Notes ##
- Tutorial on developing an API client with Guzzle Web Services:  
  <http://www.phillipshipley.com/2015/04/creating-a-php-nexmo-api-client-using-guzzle-web-service-client-part-1/>
- Presentation by Jeremy Lindblom:  
  <https://speakerdeck.com/jeremeamia/building-web-service-clients-with-guzzle-1>
- Example by Jeremy Lindblom:  
  <https://github.com/jeremeamia/sunshinephp-guzzle-examples>
- Parameter docs in source comments:  
  <https://github.com/guzzle/guzzle-services/blob/master/src/Parameter.php>
- Guzzle 3 Service Descriptions documentation (at least mostly still relevant):  
  <https://guzzle3.readthedocs.org/webservice-client/guzzle-service-descriptions.html>
