<?php

namespace Shimango\Gophr\Exceptions;

use Stringable;
use Exception;

/**
 * Class GophrException
 * @package Shimango\Gophr\Exceptions
 */
abstract class GophrException extends Exception implements Stringable
{
    /**
     * Construct a new Gophr Exception handler
     * @param string    $message  The error to send
     * @param int       $code     The error code associated with the error
     * @param Exception $previous The last error sent, defaults to null
     */
    public function __construct(string $message, int $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Stringify the returned error and message
     * @return string The returned string message
     */
    public function __toString(): string
    {
        return self::class . sprintf(': [%s]: %s%s', $this->code, $this->message, PHP_EOL);
    }
}
