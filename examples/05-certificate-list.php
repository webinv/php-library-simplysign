<?php

require_once __DIR__ . '/../vendor/autoload.php';

use SimplySign\Connection;
use SimplySign\Client\SoftCardService;

$connection = new Connection([
    'client_id' => '**client_id**',
    'client_secret' => '**client_secret**',
    'domain' => Connection::DOMAIN_TEST
]);

$client = new SoftCardService($connection);

$token = new \SimplySign\Model\Token([
    'access_token' => '*******************************************',
    'token_type' => 'bearer',
    'expires_in' => 7200,
    'refresh_token' => '*******************************************'
]);

$certificates = $client->getCertificates('**card***', $token);
foreach ($certificates as $certificate) {
    echo 'File: ' . $certificate->getFilename() . "\n\n";
    echo $certificate->getContent() . "\n\n";
}
