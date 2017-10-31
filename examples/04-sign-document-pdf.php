<?php

require_once __DIR__ . '/../vendor/autoload.php';

use SimplySign\Connection;
use SimplySign\Client\SignatureFormatServicePades;
use SimplySign\Model\Pades\SigningRequest;
use SimplySign\Model\Pades\Signing\Credentials;

$connection = new Connection([
    'client_id' => '**client_id**',
    'client_secret' => '**client_secret**',
    'domain' => Connection::DOMAIN_TEST
]);

$client = new SignatureFormatServicePades($connection);

$token = new \SimplySign\Model\Token([
    'access_token' => '*******************************************',
    'token_type' => 'bearer',
    'expires_in' => 7200,
    'refresh_token' => '*******************************************'
]);

$credentials = new Credentials();
$credentials->setCard('*******');
$credentials->setPin('*******');
$credentials->setToken($token->getAccessToken());

$signingRequest = new SigningRequest();
$signingRequest->setCertificateFile(__DIR__ . '/example.pem');
$signingRequest->setFile(__DIR__ . '/example.pdf');
$signingRequest->setCredentials($credentials);

$signed = $client->sign($signingRequest, $token);

file_put_contents(__DIR__ . '/signed_document.pdf', base64_decode($signed));
