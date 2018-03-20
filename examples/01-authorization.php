<?php

require_once __DIR__ . '/../vendor/autoload.php';

use SimplySign\Connection;
use SimplySign\Client\Authorization;

$connection = new Connection([
    'client_id' => '**client_id**',
    'client_secret' => '**client_secret**',
    'domain' => Connection::DOMAIN_TEST
]);

$client = new Authorization($connection);

$token = $client->getAccessTokenByEmailPassword('example@domain.com', '******');

print_r($token);
