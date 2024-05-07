<?php

namespace Bizhost\Authentication\Adapter\Token\Model;

class Token
{

    public function __construct(
        private readonly string    $accessToken,
        private readonly string    $refreshToken,
        private readonly int       $expiresIn,
        private readonly string    $tokenType,
        private readonly \stdClass $decoded,
        private readonly array    $scope
    )
    {
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    public function getExpiresIn(): int
    {
        return $this->expiresIn;
    }

    public function getTokenType(): string
    {
        return $this->tokenType;
    }

    public function getDecoded(): \stdClass
    {
        return $this->decoded;
    }

    public function getScope(): array
    {
        return $this->scope;
    }
}
