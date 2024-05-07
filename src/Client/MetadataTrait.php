<?php

namespace Bizhost\Authentication\Adapter\Client;

use Bizhost\Authentication\Adapter\Authenticate\Model\Metadata;
use Bizhost\Authentication\Adapter\Client\Exception\AuthClientServerException;

trait MetadataTrait
{

    public function fetchMetadata(AuthClientInterface $client, AuthClientConfig $config): void
    {
        try {
            $response = $client->sendUnAuthenticated('GET', $config->getApiUrl().$config->getIssuerMetaDataPath());

            // Transform response to Metadata
            $config->setMetadata(new Metadata(
                issuer: $response->issuer,
                publicKey: null,
                keyAlgorithm: null,
                keysEndpoint: $response->keys_endpoint,
                authorizationEndpoint: $response->authorization_endpoint,
                tokenEndpoint: $response->token_endpoint,
                tokenEndpointAuthMethodsSupported: $response->token_endpoint_auth_methods_supported,
                userInfoEndpoint: $response->userinfo_endpoint,
                registrationEndpoint: $response->registration_endpoint,
                scopesSupported: $response->scopes_supported,
                responseTypesSupported: $response->response_types_supported
            ));

        } catch (AuthClientServerException $e) {
            dd($e);
        }
    }

    public function fetchKeys(AuthClientInterface $client, AuthClientConfig $config): void
    {
        try {
            $metaData = $config->getMetadata();

            $response = $client->sendUnAuthenticated('GET', $config->getMetadata()->getKeysEndpoint());

            if(gettype($response[0]) !== 'object' || !$response[0]->RS256) {
                return;
            }
            $responseKey = $response[0]->RS256->key ?? null;

            if(!$responseKey) {
                return;
            }

            // Transform response to Metadata
            $this->config->setMetadata(new Metadata(
                issuer: $metaData->getIssuer(),
                publicKey: $responseKey,
                keyAlgorithm: 'RS256',
                keysEndpoint: $metaData->getKeysEndpoint(),
                authorizationEndpoint: $metaData->getAuthorizationEndpoint(),
                tokenEndpoint: $metaData->getTokenEndpoint(),
                tokenEndpointAuthMethodsSupported: $metaData->getTokenEndpointAuthMethodsSupported(),
                userInfoEndpoint: $metaData->getUserInfoEndpoint(),
                registrationEndpoint: $metaData->getRegistrationEndpoint(),
                scopesSupported: $metaData->getScopesSupported(),
                responseTypesSupported: $metaData->getResponseTypesSupported()
            ));

        } catch (AuthClientServerException $e) {

        }
    }

}
