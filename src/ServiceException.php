<?php
namespace Sil\Idp\IdBroker\Client;

use Throwable;

class ServiceException extends \Exception
{
    /**
     * @var int The status code returned by ID Broker API
     */
    public $httpStatusCode;

    public function __construct($message = "", $code = 0, $httpStatusCode = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}