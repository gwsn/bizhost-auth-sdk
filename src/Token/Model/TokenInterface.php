<?php

namespace Bizhost\Authentication\Adapter\Token\Model;

interface TokenInterface
{
    public function getAccessToken(): string;

    public function getRefreshToken(): string | null;

    public function getExpiresIn(): int;

    public function getTokenType(): string;

    public function getDecoded(): \stdClass;

    public function getScope(): ?array;

}
