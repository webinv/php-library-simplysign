<?php
/**
 * SimplySign WebService
 */

namespace SimplySign;

use GuzzleHttp\ClientInterface;

/**
 * Class WebService
 *
 * @package SimplySign
 * @author Krzysztof Kardasz <krzysztof@kardasz.eu>
 */
class Connection
{
    /**
     * @var
     */
    private $clientId;

    /**
     * @var
     */
    private $clientSecret;

    const DOMAIN_PRODUCTION = 'https://cloudsign.webnotarius.pl';
    const DOMAIN_TEST = 'https://model.simplysign.webnotarius.pl';
    const DOMAIN_DEV = 'https://dev.simplysign.webnotarius.pl';

    /**
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * @var string
     */
    private $domain;

    /**
     * WebService constructor.
     *
     * @param array $config
     * @param ClientInterface|null $httpClient
     */
    public function __construct(array $config = [], ClientInterface $httpClient = null)
    {
        if (isset($config['domain'])) {
            $this->setDomain($config['domain']);
        }

        if (isset($config['client_id'])) {
            $this->setClientId($config['client_id']);
        }

        if (isset($config['client_secret'])) {
            $this->setClientSecret($config['client_secret']);
        }
    }


    /**
     * @return ClientInterface
     */
    public function getHttpClient()
    {
        if (null === $this->httpClient) {
            $this->httpClient = new \GuzzleHttp\Client();
        }
        return $this->httpClient;
    }

    /**
     * @param ClientInterface $httpClient
     */
    public function setHttpClient($httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @return mixed
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param mixed $clientId
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }

    /**
     * @return mixed
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * @param mixed $clientSecret
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;
    }

    /**
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param string $domain
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }
}