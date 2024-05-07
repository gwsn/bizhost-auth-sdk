<?php

use Bizhost\Authentication\Adapter\Adapter\AuthService;

require_once __DIR__ . '/vendor/autoload.php';

$authService = new AuthService(
    $clientId = 'your-client-id',
    $clientSecret = 'your-client-secret',
    $redirectUrl = 'http://localhost:8000/code-flow.php',
    $issuerMetaDataUrl = 'https://auth.bizhost.cloud/.well-known/openid-configuration',
);


$authService->initialize();

$authService->startClientCredentialsFlow();

