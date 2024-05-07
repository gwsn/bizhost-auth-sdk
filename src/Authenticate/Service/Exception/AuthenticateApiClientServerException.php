<?php

declare(strict_types=1);

namespace Bizhost\Authentication\Adapter\Authenticate\Service\Exception;

use Bizhost\Authentication\Adapter\Client\Exception\AuthClientServerException;

class AuthenticateApiClientServerException extends AuthClientServerException
{
    public function __construct(int $statusCode = 500, $message = '', $code = 0, \Throwable $previous = null)
    {
        $message = 'Error in Authenticate Bizhost Auth API Client and cannot recover: ' . $message;
        $message = '[' . $statusCode . '] ' . $message;

        parent::__construct($message, $code === 0 ? $statusCode : $code, $previous);
    }
}
