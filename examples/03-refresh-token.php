<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Webinv\SimplySign\Client\Authorization;
use Webinv\SimplySign\Connection;
use Webinv\SimplySign\Model\Token;

$connection = new Connection([
    'client_id' => '**client_id**',
    'client_secret' => '**client_secret**',
    'domain' => Connection::DOMAIN_TEST
]);

$client = new Authorization($connection);

$token = new Token([
    'access_token' => '*******************************************',
    'token_type' => 'bearer',
    'expires_in' => 7200,
    'refresh_token' => '*******************************************'
]);

$client->refreshToken($token);

print_r($token);
