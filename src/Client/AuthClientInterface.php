<?php

namespace Bizhost\Authentication\Adapter\Client;

use GuzzleHttp\Client as GuzzleClient;
use Bizhost\Authentication\Adapter\Client\Exception\AuthClientServerException;

interface AuthClientInterface {

    public function __construct(
        AuthClientConfig $config
    );

    public function setClient(GuzzleClient $client = null): void;

    public function getClient(): GuzzleClient;

    /**
     * @throws AuthClientServerException
     */
    public function send(string $method = 'GET', string $urlPath = '', array $data = null, array $headers = []): mixed;
}
