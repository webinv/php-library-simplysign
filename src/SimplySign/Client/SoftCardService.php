<?php
/**
 * SimplySign WebService Client
 */

namespace SimplySign\Client;

use SimplySign\Client;
use SimplySign\Exception;
use SimplySign\Model\SoftCard\Certificate;
use SimplySign\Model\Token;
use SimplySign\Response\MultipartResponse;
use SimplySign\Client\SoftCardService\SoftCardServiceException;

/**
 * Class SoftCardService
 *
 * @package SimplySign\Client
 * @author Krzysztof Kardasz <krzysztof@kardasz.eu>
 */
class SoftCardService extends Client
{
    /**
     * @param Token $token
     * @return mixed
     * @throws Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function createCardsTask (Token $token)
    {
        $response = $this->getConnection()->getHttpClient()->request(
            'POST',
            sprintf('%s/card/v1/cards/tasks', $this->getConnection()->getDomain()),
            [
                'headers' => [
                    'Authorization' => sprintf(
                        '%s %s',
                        $token->getTokenType(),
                        $token->getAccessToken()
                    ),
                    'Accept' => 'application/json'
                ],
                'allow_redirects' => false
            ]
        );

        return  $this->_parseResponse($response);
    }

    /**
     * @param $card
     * @param Token $token
     * @return mixed
     * @throws Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function createCardCertificatesTask ($card, Token $token)
    {
        $response = $this->getConnection()->getHttpClient()->request(
            'POST',
            sprintf('%s/card/v1/cards/%s/certificates/tasks', $this->getConnection()->getDomain(), $card),
            [
                'headers' => [
                    'Authorization' => sprintf(
                        '%s %s',
                        $token->getTokenType(),
                        $token->getAccessToken()
                    ),
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json;charset=UTF-8'
                ],
                'allow_redirects' => false
            ]
        );

        return $this->_parseResponse($response);
    }

    /**
     * @param $link
     * @param Token $token
     * @return mixed
     * @throws Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getTask ($link, Token $token)
    {
        $url = sprintf('%s%s', $this->getConnection()->getDomain(), parse_url ($link,PHP_URL_PATH));
        $response = $this->getConnection()->getHttpClient()->request(
            'GET',
            $url,
            [
                'headers' => [
                    'Authorization' => sprintf(
                        '%s %s',
                        $token->getTokenType(),
                        $token->getAccessToken()
                    ),
                    'Accept' => 'application/json'
                ],
                'allow_redirects' => false
            ]
        );

        return $this->_parseResponse($response);
    }

    /**
     * @param $card
     * @param Token $token
     * @return Certificate[]
     * @throws Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getCertificates ($card, Token $token)
    {
        $results = $this->createCardCertificatesTask($card, $token);

        if (!isset($results['state'])) {
            throw new Exception('Invalid response, missing "state" param');
        }

        if ($results['state'] == 'pending' && isset($results['ping-after'])) {
            usleep((int)$results['ping-after']);
        }

        if (!isset($results['atom:link'])) {
            throw new Exception('Invalid response, missing "atom:link" param');
        }

        $results = $this->getTask($results['atom:link'], $token);

        if (!isset($results['state'])) {
            throw new Exception('Invalid response, missing "state" param');
        }

        if ($results['state'] != 'done') {
            print_r($results);
            throw new Exception(sprintf('Invalid response, state: %s', $results['state']));
        }

        if (!isset($results['atom:link'])) {
            throw new Exception('Invalid response, missing "atom:link" param');
        }

        $response = $this->getConnection()->getHttpClient()->request(
            'GET',
            sprintf('%s%s', $this->getConnection()->getDomain(), parse_url ($results['atom:link'],PHP_URL_PATH)),
            [
                'headers' => [
                    'Authorization' => sprintf(
                        '%s %s',
                        $token->getTokenType(),
                        $token->getAccessToken()
                    ),
                    'Accept' => 'multipart/form-data, application/json'
                ],
                'allow_redirects' => false
            ]
        );

        $multipartResponse = new MultipartResponse($response);
        if (!$multipartResponse->isMultipart()) {
            throw new SoftCardServiceException(
                'Invalid response, multipart expected'
            );
        }

        if (!$multipartResponse->hasItem('certificate') || !$multipartResponse->hasItem('res')) {
            throw new SoftCardServiceException(
                'Invalid response, multipart/form-data  certificate or res missing'
            );
        }

        $certificates = [];
        $details = [];

        foreach ($multipartResponse->getItem('res') as $item) {
            if (0 !== strcasecmp($item->getContentType(), 'application/json')) {
                throw new SoftCardServiceException(
                    sprintf('Invalid response, multipart/form-data "res" invalid Content-type: "%s"', $item->getContentType())
                );
            }

            $collection = json_decode((string)$item->getBody(), true);
            foreach ($collection as $json) {
                if (isset($json['filename'])) {
                    $details[$json['filename']] = $json;
                }
            }
        }

        foreach ($multipartResponse->getItem('certificate') as $item) {
            $options = $item->getContentDispositionOptions();
            if (!isset($options['filename'])) {
                throw new SoftCardServiceException(
                    'Invalid response, multipart/form-data certificate filename missing'
                );
            }

            if (!isset($details[$options['filename']])) {
                throw new SoftCardServiceException(
                    sprintf('Invalid response, multipart/form-data certificate filename "%s" json missing', $options['filename'])
                );
            }

            $certificates[] = new Certificate(
                $details[$options['filename']],
                $item->getBody()
            );
        }

        return $certificates;
    }
}