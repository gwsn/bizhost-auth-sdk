<?php

namespace Examples;

use Bizhost\Authentication\Adapter\Account\Service\AccountService;
use Bizhost\Authentication\Adapter\Authenticate\Service\AuthenticateService;
use Bizhost\Authentication\Adapter\Client\AuthClientConfig;
use Bizhost\Authentication\Adapter\Token\Model\Token;

require_once __DIR__ . '../vendor/autoload.php';

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

if (isset($_GET['code'])) {
    // Validate the code and get the JWT token
    /* @var $jwtToken Token */
    $token = $authService->getAccessTokenByCodeFlow($_GET['code']);

    var_dump($token);

    $accountService = new AccountService($config, $token);
    $account = $accountService->getCurrentAccount();

    var_dump($account);

} else {
    $url = $authService->generateCodeFlowUrl();
    header("Location: ${$url}");

}
