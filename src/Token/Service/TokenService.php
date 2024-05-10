<?php

namespace Bizhost\Authentication\Adapter\Token\Service;

use Bizhost\Authentication\Adapter\Authenticate\Service\MetadataApiClient;
use Bizhost\Authentication\Adapter\Client\AuthClientConfig;
use Bizhost\Authentication\Adapter\Client\MetadataTrait;
use Bizhost\Authentication\Adapter\Token\Model\Token;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class TokenService {

    use MetadataTrait;

    private MetadataApiClient $client;

    public function __construct(
        private readonly AuthClientConfig $config
    ){
        $this->client = new MetadataApiClient($this->config);
        $this->fetchMetadata($this->client, $this->config);
    }

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

    public static function base64UrlEncode(string $body): string
    {
        $base64 = base64_encode($body);
        $base64 = trim($base64, '=');

        return strtr($base64, '+/', '-_');
    }

    public static function pkceChallenge(): array
    {
        $random = bin2hex(openssl_random_pseudo_bytes(32));
        $verifier = self::base64UrlEncode(pack('H*', $random));
        $challenge = self::base64UrlEncode(pack('H*', hash('sha256', $verifier)));

        return [
            'verifier' => $verifier,
            'challenge' => $challenge,
        ];
    }

    public static function decodeJWT(string $token): array
    {
        $parts = explode('.', $token);
        $headers = self::decodeJWTPart($parts[0] ?? null);
        $payload = self::decodeJWTPart($parts[1] ?? null);

        return [
            'headers' => $headers,
            'payload' => $payload,
        ];
    }

    public static function decodeJWTPart(?string $jwTokenPart): ?array
    {
        if ($jwTokenPart === null) {
            return [];
        }

        // Sanitize the string:
        $base64Encoded = str_replace('_', '/', str_replace('-', '+', $jwTokenPart));

        return json_decode(base64_decode($base64Encoded, true), true);
    }
}
