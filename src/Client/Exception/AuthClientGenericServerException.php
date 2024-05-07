<?php

declare(strict_types=1);

namespace Bizhost\Authentication\Adapter\Client\Exception;

class AuthClientGenericServerException extends AuthClientServerException
{
    public function __construct(int $statusCode = 500, $message = '', $code = 0, \Throwable $previous = null)
    {
        $message = 'Error in Bizhost Auth API Client and cannot recover: ' . $message;
        $message = '[' . $statusCode . '] ' . $message;

        parent::__construct($message, $code, $previous);
    }
}
