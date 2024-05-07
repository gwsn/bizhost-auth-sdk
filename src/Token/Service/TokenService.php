<?php

namespace Bizhost\Authentication\Adapter\Token\Service;

use Bizhost\Authentication\Adapter\Client\AuthClientConfig;
use Bizhost\Authentication\Adapter\Token\Model\Token;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class TokenService {

    public function __construct(
        private readonly AuthClientConfig $config
    ){}


    public function decodeTokenResponse(object $tokenResponse): ?Token {

        $tokenPayload = $this->decodeAccessToken($tokenResponse->access_token);

        return new Token(
            accessToken: $tokenResponse->access_token,
            refreshToken: $tokenResponse->refresh_token,
            expiresIn: $tokenResponse->expires_in,
            tokenType: $tokenResponse->token_type,
            decoded: $tokenPayload,
            scope: $tokenPayload->scopes,
        );
    }

    // decode JWT Token into Token object
    public function decodeAccessToken(string $token): \stdClass {

        $publicKey = $this->config->getMetadata()->getPublicKey();
        $keyAlgorithm = $this->config->getMetadata()->getKeyAlgorithm();

        if($publicKey === null) {
            throw new \RuntimeException('Public key not set');
        }

        if($keyAlgorithm === null) {
            throw new \RuntimeException('Key algorithm not set');
        }

        $headers = new \stdClass();
        return JWT::decode($token, new Key($publicKey, $keyAlgorithm), $headers);
    }
}
