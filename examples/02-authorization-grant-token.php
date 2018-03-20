<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Webinv\SimplySign\Connection;
use Webinv\SimplySign\Client\Authorization;

$connection = new Connection([
    'client_id' => '**client_id**',
    'client_secret' => '**client_secret**',
    'domain' => Connection::DOMAIN_TEST
]);

$client = new Authorization($connection);
$redirectUrl = 'http://example.com/02-authorization-grant-token.php';

if (isset($_GET['code'])) {
    $token = $client->getAccessTokenByAuthorizationCode($redirectUrl, $_GET['code']);
    print_r($token);
} else {
    header(sprintf('Location: %s', $client->getAuthorizationUrl($redirectUrl)));
}
