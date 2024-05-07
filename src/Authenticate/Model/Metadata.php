<?php

namespace Bizhost\Authentication\Adapter\Authenticate\Model;

class Metadata
{
    public function __construct(
        private readonly string $issuer,
        private readonly string|null $publicKey,
        private readonly string|null $keyAlgorithm,
        private readonly string $keysEndpoint,
        private readonly string $authorizationEndpoint,
        private readonly string $tokenEndpoint,
        private readonly array  $tokenEndpointAuthMethodsSupported,
        private readonly string $userInfoEndpoint,
        private readonly string $registrationEndpoint,
        private readonly array  $scopesSupported,
        private readonly array  $responseTypesSupported
    )
    {
    }

    public function getIssuer(): string
    {
        return $this->issuer;
    }

    public function getPublicKey(): ?string
    {
        return $this->publicKey;
    }

    public function getKeyAlgorithm(): ?string
    {
        return $this->keyAlgorithm;
    }

    public function getKeysEndpoint(): string
    {
        return $this->keysEndpoint;
    }

    public function getAuthorizationEndpoint(): string
    {
        return $this->authorizationEndpoint;
    }

    public function getTokenEndpoint(): string
    {
        return $this->tokenEndpoint;
    }

    public function getTokenEndpointAuthMethodsSupported(): array
    {
        return $this->tokenEndpointAuthMethodsSupported;
    }

    public function getUserInfoEndpoint(): string
    {
        return $this->userInfoEndpoint;
    }

    public function getRegistrationEndpoint(): string
    {
        return $this->registrationEndpoint;
    }

    public function getScopesSupported(): array
    {
        return $this->scopesSupported;
    }

    public function getResponseTypesSupported(): array
    {
        return $this->responseTypesSupported;
    }

}
