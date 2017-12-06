<?php
namespace Sil\Idp\IdBroker\Client;

use Throwable;

class ServiceException extends \Exception
{
    /**
     * @var null|int The status code returned by ID Broker API
     */
    public $httpStatusCode;

    public function __construct($message = "", $code = 0, $httpStatusCode = null, Throwable $previous = null)
    {
        $this->httpStatusCode = $httpStatusCode;
        parent::__construct($message, $code, $previous);
    }
}