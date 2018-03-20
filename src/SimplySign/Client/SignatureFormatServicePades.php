<?php
/**
 * SimplySign WebService Client
 */

namespace Webinv\SimplySign\Client;

use Webinv\SimplySign\Client;
use Webinv\SimplySign\Exception;
use Webinv\SimplySign\Model\Pades\SigningRequest;
use Ramsey\Uuid\Uuid;
use Webinv\SimplySign\Model\Token;

/**
 * Class SignatureFormatServicePades
 *
 * @package Webinv\SimplySign\Client
 * @author Krzysztof Kardasz <krzysztof@kardasz.eu>
 */
class SignatureFormatServicePades extends Client
{
    /**
     * @param SigningRequest $signingRequest
     * @param Token $token
     * @return mixed
     * @throws Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sign(SigningRequest $signingRequest, Token $token)
    {
        $response = $this->getConnection()->getHttpClient()->request(
            'POST',
            sprintf('%s/sfs/1.0/services', $this->getConnection()->getDomain()),
            [
                'headers' => [
                    'Authorization' => sprintf(
                        '%s %s',
                        $token->getTokenType(),
                        $token->getAccessToken()
                    ),
                    'Accept' => 'application/json'
                ],
                'json' => [
                    'id' => Uuid::uuid4()->toString(),
                    'jsonrpc' => '2.0',
                    'method' => 'sign',
                    'params' => $signingRequest
                ]
            ]
        );

        $json = $this->_parseResponse($response);

        if (isset($json['error'])) {
            throw new Exception(
                $json['error']['message'],
                $json['error']['code'],
                $json['error']['data']
            );
        }

        if (isset($json['result'])) {
            return $json['result'];
        }
    }
}
