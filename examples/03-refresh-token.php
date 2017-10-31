<?php

require_once __DIR__ . '/../vendor/autoload.php';

use SimplySign\Client\Authorization;
use SimplySign\Connection;

$connection = new Connection([
    'client_id' => '**client_id**',
    'client_secret' => '**client_secret**',
    'domain' => Connection::DOMAIN_TEST
]);

$client = new Authorization($connection);

$oldToken = new \SimplySign\Model\Token([
    'access_token' => '*******************************************',
    'token_type' => 'bearer',
    'expires_in' => 7200,
    'refresh_token' => '*******************************************'
]);

$newToken = $client->refreshToken($oldToken);

print_r($newToken);