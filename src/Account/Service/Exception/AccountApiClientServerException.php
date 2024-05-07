<?php

declare(strict_types=1);

namespace Bizhost\Authentication\Adapter\Account\Service\Exception;

use Bizhost\Authentication\Adapter\Client\Exception\AuthClientServerException;

class AccountApiClientServerException extends AuthClientServerException
{
    public function __construct(int $statusCode = 500, $message = '', $code = 0, \Throwable $previous = null)
    {
        $message = 'Error in Account Bizhost Auth API Client and cannot recover: ' . $message;
        $message = '[' . $statusCode . '] ' . $message;

        parent::__construct($message, $code === 0 ? $statusCode : $code, $previous);
    }
}
