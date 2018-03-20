<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Webinv\SimplySign\Connection;
use Webinv\SimplySign\Client\SignatureFormatServicePades;
use Webinv\SimplySign\Model\Pades\SigningRequest;
use Webinv\SimplySign\Model\Pades\Signing\Credentials;
use Webinv\SimplySign\Model\Token;

$connection = new Connection([
    'client_id' => '**client_id**',
    'client_secret' => '**client_secret**',
    'domain' => Connection::DOMAIN_TEST
]);

$client = new SignatureFormatServicePades($connection);

$token = new Token([
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
