<?php

use Bizhost\Authentication\Adapter\Authenticate\Service\AuthenticateService;
use Bizhost\Authentication\Adapter\Client\AuthClientConfig;

require_once __DIR__ . '/vendor/autoload.php';

$apiUrl = 'https://auth-test.bizhost.nl';
$clientId = 'your-client-id';
$clientSecret = 'your-client-secret';
$redirectUrl = 'http://localhost:8000/code-flow.php';
$issuerMetaDataPath = '/.well-known/oauth-authorization-server';

$config = new AuthClientConfig(
    apiUrl: $apiUrl,
    clientId: $clientId,
    clientSecret: $clientSecret,
    redirectUrl: $redirectUrl,
    issuerMetaDataPath: $issuerMetaDataPath
);

$authService = new AuthenticateService(
    $config,
);

