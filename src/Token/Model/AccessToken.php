<?php

namespace Bizhost\Authentication\Adapter\Token\Model;

class AccessToken implements TokenInterface
{

    public function __construct(
        private readonly string    $accessToken,
        private readonly \stdClass $decoded
    )
    {
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getRefreshToken(): string|null
    {
        return null;
    }

    public function getExpiresIn(): int
    {
        return 28800;
    }

    public function getTokenType(): string
    {
        return 'Bearer';
    }

    public function getDecoded(): \stdClass
    {
        return $this->decoded;
    }

    public function getScope(): array|null
    {
        return null;
    }
}
