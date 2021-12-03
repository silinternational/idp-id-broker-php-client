<?php
namespace Sil\Idp\IdBroker\Client;

use Throwable;

class ServiceException extends \Exception
{
    /**
     * @var null|int The status code returned by ID Broker API
     */
    public ?int $httpStatusCode;

    /**
     * @param string $message
     * @param int $code
     * @param int|null $httpStatusCode
     * @param Throwable|null $previous
     */
    public function __construct(string $message = "", int $code = 0, ?int $httpStatusCode = null, Throwable $previous = null)
    {
        $this->httpStatusCode = $httpStatusCode;
        parent::__construct($message, $code, $previous);
    }
}