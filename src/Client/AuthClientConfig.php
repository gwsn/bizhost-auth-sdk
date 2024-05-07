<?php

namespace Bizhost\Authentication\Adapter\Client;

use Bizhost\Authentication\Adapter\Authenticate\Model\Metadata;

class AuthClientConfig {
    private ?Metadata $metadata = null;

    public function __construct(
        private readonly string $apiUrl,
        private readonly string $clientId,
        private readonly string $clientSecret,
        private readonly string $redirectUrl,
        private readonly string $issuerMetaDataPath,
        private readonly float $timeout = 10.0
    ) {
    }

    public function getApiUrl(): string
    {
        return $this->apiUrl;
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    public function getRedirectUrl(): string
    {
        return $this->redirectUrl;
    }

    public function getIssuerMetaDataPath(): string
    {
        return $this->issuerMetaDataPath;
    }

    public function getTimeout(): float
    {
        return $this->timeout;
    }

    public function getMetadata(): ?Metadata
    {
        return $this->metadata;
    }

    public function setMetadata(Metadata $metadata): void
    {
        $this->metadata = $metadata;
    }
}
