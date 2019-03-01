<?php
/**
 * SimplySign Xades Atom Client
 */

namespace Webinv\SimplySign\Client;

use Webinv\SimplySign\Client;
use Webinv\SimplySign\Exception;
use Webinv\SimplySign\Model\Xades\SigningRequest;
use Ramsey\Uuid\Uuid;
use Webinv\SimplySign\Model\Token;

/**
 * Class SignatureFormatServiceXades - Enveloped & Enveloping
 *
 * @package Webinv\SimplySign\Client
 * @author <li-on@wp.pl>
 */
class SignatureServiceXades extends Client
{
    /**
     * @param SigningRequest $signingRequest
     * @param Token $token
     * @return mixed
     * @throws Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function createSignTask(SigningRequest $signingRequest, Token $token)
    {
        $response = $this->getConnection()->getHttpClient()->request(
            'POST',
            sprintf('%s/sfsx/1.0/signature', $this->getConnection()->getDomain()),
            [
                'headers' => [
                    'Authorization' => sprintf(
                        '%s %s',
                        $token->getTokenType(),
                        $token->getAccessToken()
                    ),
                    'Accept' => 'application/json'
                ],
                'multipart' => [
                    [
                        'name'     => 'certificate',
                        'filename' => 'cert.pem',
                        'contents' => $signingRequest->getCertificate()
                    ],
                    [
                        'name'     => 'files',
                        'filename' => empty($signingRequest->getFileName())?'file.xml':$signingRequest->getFileName(),
                        'contents' => $signingRequest->getFile(),
                        //'headers'  => ['Content-Type' => 'application/xml']
                    ],
                    [
                        'name'     => 'params',
                        'filename' => 'profile.json',
                        'contents' => json_encode($signingRequest)
                        
                    ],
                ]
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
    public function getTask($link, Token $token)
    {
        $url = sprintf('%s%s', $this->getConnection()->getDomain(), parse_url($link, PHP_URL_PATH));
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
    
    public function sign(SigningRequest $signingRequest, Token $token){
        $results = $this->createSignTask($signingRequest, $token);
        
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
        
        if (!isset($results['atom:link'])) {
            throw new Exception('Invalid response, missing "atom:link" param');
        }
        //service sometimes need more time
        if ($results['state'] == 'pending' && isset($results['ping-after'])) {
            usleep((int)$results['ping-after']);
            
            $results = $this->getTask($results['atom:link'], $token);
            
            if (!isset($results['state'])) {
                throw new Exception('Invalid response, missing "state" param');
            }
            
            if (!isset($results['atom:link'])) {
                throw new Exception('Invalid response, missing "atom:link" param');
            }
        }

        if ($results['state'] != 'done') {
            throw new Exception(sprintf('Invalid response, state: %s', $results['state']));
        }

        $response = $this->getConnection()->getHttpClient()->request(
            'GET',
            sprintf('%s%s', $this->getConnection()->getDomain(), parse_url($results['atom:link'], PHP_URL_PATH)),
            [
                'headers' => [
                    'Authorization' => sprintf(
                        '%s %s',
                        $token->getTokenType(),
                        $token->getAccessToken()
                    ),
                    'Accept' => 'application/xml, application/json'
                ],
                'allow_redirects' => false
            ]
        );

        $contentType=$response->getHeader('Content-Type');
        if(is_array($contentType)) $contentType=reset($contentType);
        
        if($contentType == 'application/xml'){
            return (string)$response->getBody();
        }else if($contentType == 'application/json'){
            $status = $this->_parseResponse($response);
            if(!empty($status['message'])){
                throw new Exception(sprintf('Server error: %s',$status['message']));
            }else{
                throw new Exception(sprintf('Invalid response content-type: %s',$contentType));
            }
        }
    }
}
