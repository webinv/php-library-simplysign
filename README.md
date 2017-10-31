# About

SimplySign webservice client

## 1. Configuration

```
use SimplySign\Connection;
use SimplySign\Client\Authorization;

$connection = new Connection([
    'client_id' => '**client_id**',
    'client_secret' => '**client_secret**',
    'domain' => Connection::DOMAIN_TEST
]);
```

## 2. Authorization

### 2.1 Authorization Code

```
use SimplySign\Client\Authorization;

$client = new Authorization($connection);
$redirectUrl = 'http://example.com/02-authorization-grant-token.php';

if (isset($_GET['code'])) {
    $token = $client->getAccessTokenByAuthorizationCode($redirectUrl, $_GET['code']);
    print_r($token);
} else {
    header(sprintf('Location: %s', $client->getAuthorizationUrl($redirectUrl)));
}
```
### 2.2 Resource Owner Password Credentials

```
use SimplySign\Client\Authorization;

$client = new Authorization($connection);
$token = $client->getAccessTokenByEmailPassword('example@domain.com', '******');
```

## 2. Signing PDF document

```
use SimplySign\Client\SignatureFormatServicePades;
use SimplySign\Model\Pades\SigningRequest;
use SimplySign\Model\Pades\Signing\Credentials;


$client = new SignatureFormatServicePades($connection);

// Create token or you can obrain a new one
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
```