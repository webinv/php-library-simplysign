<?php
/**
 * SimplySign WebService Client
 */

namespace Webinv\SimplySign;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

/**
 * Class WebService
 *
 * @package Webinv\SimplySign
 * @author Krzysztof Kardasz <krzysztof@kardasz.eu>
 */
class Connection implements LoggerAwareInterface
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
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var string
     * @see https://github.com/guzzle/guzzle/blob/master/src/MessageFormatter.php
     */
    private $requestLogFormat = '{request}';

    /**
     * @var string
     * @see https://github.com/guzzle/guzzle/blob/master/src/MessageFormatter.php
     */
    private $responseLogFormat = '{response}';

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

        if (isset($config['request_log_format'])) {
            $this->setRequestLogFormat($config['request_log_format']);
        }

        if (isset($config['response_log_format'])) {
            $this->setResponseLogFormat($config['response_log_format']);
        }
    }


    /**
     * @return ClientInterface
     */
    public function getHttpClient()
    {
        if (null === $this->httpClient) {
            $config = [];

            if (null !== $this->logger) {
                $stack = HandlerStack::create();

                $stack->push(Middleware::log(
                    $this->logger,
                    new MessageFormatter($this->getRequestLogFormat())
                ));

                $stack->push(Middleware::log(
                    $this->logger,
                    new MessageFormatter($this->getResponseLogFormat())
                ));

                $config['handler'] = $stack;
            }

            $this->httpClient = new \GuzzleHttp\Client($config);
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

    /**
     * @return string
     */
    public function getRequestLogFormat()
    {
        return $this->requestLogFormat;
    }

    /**
     * @param string $requestLogFormat
     * @see https://github.com/guzzle/guzzle/blob/master/src/MessageFormatter.php
     */
    public function setRequestLogFormat($requestLogFormat)
    {
        $this->requestLogFormat = $requestLogFormat;
    }

    /**
     * @return string
     */
    public function getResponseLogFormat()
    {
        return $this->responseLogFormat;
    }

    /**
     * @param string $responseLogFormat
     * @see https://github.com/guzzle/guzzle/blob/master/src/MessageFormatter.php
     */
    public function setResponseLogFormat($responseLogFormat)
    {
        $this->responseLogFormat = $responseLogFormat;
    }

    /**
     * Sets a logger instance on the object.
     *
     * @param LoggerInterface $logger
     *
     * @return void
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}
