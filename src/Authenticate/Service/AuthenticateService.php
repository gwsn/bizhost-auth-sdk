<?php

namespace Bizhost\Authentication\Adapter\Authenticate\Service;

use Bizhost\Authentication\Adapter\Authenticate\Model\Metadata;
use Bizhost\Authentication\Adapter\Client\AuthClientConfig;
use Bizhost\Authentication\Adapter\Client\Exception\AuthClientServerException;
use Bizhost\Authentication\Adapter\Client\MetadataTrait;
use Bizhost\Authentication\Adapter\Token\Model\Token;
use Bizhost\Authentication\Adapter\Token\Service\TokenService;

class AuthenticateService
{
    use MetadataTrait;

    protected AuthenticateApiClient $client;
    protected TokenService $tokenService;

    public function __construct(
        protected readonly AuthClientConfig $config,
    )
    {
        $this->client = new AuthenticateApiClient($this->config);
        $this->tokenService = new TokenService($this->config);
        $this->fetchMetadata($this->client, $this->config);
        $this->client->setAuthentication($this->config->getClientId(), $this->config->getClientSecret());
    }

    public function getMetadata(): ?Metadata
    {
        if($this->config->getMetadata() === null) {
            $this->fetchMetadata($this->client, $this->config);
        }

        return $this->config->getMetadata();
    }


    public function generateCodeFlowUrl(): string
    {
        return sprintf('%s?response_type=code&client_id=%s&redirect_uri=%s&state=%s',
            $this->getMetadata()->getAuthorizationEndpoint(),
            $this->config->getClientId(),
            $this->config->getRedirectUrl(),
            bin2hex(random_bytes(16))
        );
    }

    // getAccessTokenByCodeFlow
    public function getAccessTokenByCodeFlow(string $code): ?Token
    {
        try {
            $response = $this->client->send('POST', $this->getMetadata()->getTokenEndpoint(), [
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => $this->config->getRedirectUrl(),
                'client_id' => $this->config->getClientId(),
                'client_secret' => $this->config->getClientSecret(),
            ]);

            $this->fetchKeys($this->client, $this->config);

            return $this->tokenService->decodeTokenResponse($response);
        } catch (AuthClientServerException $e) {
            return null;
        }
    }

    // getAccessTokenByRefreshToken
    public function getAccessTokenByRefreshToken(string $refreshToken): ?Token
    {
        try {
            $response = $this->client->send('POST', $this->getMetadata()->getTokenEndpoint(), [
                'grant_type' => 'refresh_token',
                'refresh_token' => $refreshToken,
                'client_id' => $this->config->getClientId(),
                'client_secret' => $this->config->getClientSecret(),
            ]);

            $this->fetchKeys($this->client, $this->config);

            return $this->tokenService->decodeTokenResponse($response);
        } catch (AuthClientServerException $e) {
            return null;
        }
    }

    // getAccessTokenByClientCredentials
    public function getAccessTokenByClientCredentials(): ?Token
    {
        try {
            $response = $this->client->send('POST', $this->getMetadata()->getTokenEndpoint(), [
                'grant_type' => 'client_credentials',
                'client_id' => $this->config->getClientId(),
                'client_secret' => $this->config->getClientSecret(),
            ]);

            $this->fetchKeys($this->client, $this->config);

            return $this->tokenService->decodeTokenResponse($response);
        } catch (AuthClientServerException $e) {
            return null;
        }
    }

}
