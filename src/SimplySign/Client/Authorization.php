<?php

namespace SimplySign\Client;

use Psr\Http\Message\ResponseInterface;
use SimplySign\Client;
use SimplySign\Exception;
use SimplySign\Model\Token;

/**
 * Class Authorization
 *
 * @package SimplySign\Client
 * @author Krzysztof Kardasz <krzysztof@kardasz.eu>
 */
class Authorization extends Client
{
    /**
     * @param $redirectUrl
     * @return string
     */
    public function getAuthorizationUrl ($redirectUrl)
    {
        return sprintf(
            '%s/idp/oauth2.0/authorize?response_type=code&client_id=%s&redirect_uri=%s',
            $this->getConnection()->getDomain(),
            $this->getConnection()->getClientId(),
            $redirectUrl
        );
    }

    /**
     * @param $redirectUrl
     * @param $authorizationCode
     * @return Token
     */
    public function getAccessTokenByAuthorizationCode($redirectUrl, $authorizationCode)
    {
        $response = $this->getConnection()->getHttpClient()->request(
            'POST',
            sprintf('%s/idp/oauth2.0/accessToken', $this->getConnection()->getDomain()),
            [
                'form_params' =>[
                    'grant_type' => 'authorization_code',
                    'client_id' => $this->getConnection()->getClientId(),
                    'client_secret' => $this->getConnection()->getClientSecret(),
                    'code' => $authorizationCode,
                    'redirect_uri' => $redirectUrl
                ]
            ]
        );

        return $this->_parseResponseToken($response);
    }

    /**
     * @param $email
     * @param $password
     * @return Token
     * @throws Exception
     */
    public function getAccessTokenByEmailPassword ($email, $password)
    {
        $response = $this->getConnection()->getHttpClient()->request(
            'POST',
            sprintf('%s/idp/oauth2.0/accessToken', $this->getConnection()->getDomain()),
            [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => $this->getConnection()->getClientId(),
                    'username' => $email,
                    'password' => $password
                ]
            ]
        );

        return $this->_parseResponseToken($response);
    }

    /**
     * @param Token $token
     * @return Token
     */
    public function refreshToken (Token $token)
    {
        if ($token->isExpired()) {
            $response = $this->getConnection()->getHttpClient()->request(
                'POST',
                sprintf('%s/idp/oauth2.0/accessToken', $this->getConnection()->getDomain()),
                [
                    'form_params' => [
                        'grant_type' => 'refresh_token',
                        'client_id' => $this->getConnection()->getClientId(),
                        'client_secret' => $this->getConnection()->getClientSecret(),
                        'refresh_token' => $token->getRefreshToken()
                    ]
                ]
            );

            return $this->_parseResponseToken($response);
        }
    }

    /**
     * @param ResponseInterface $response
     * @return Token
     * @throws Exception
     */
    private function _parseResponseToken(ResponseInterface $response)
    {
        if ($response->getStatusCode() === 200) {
            return new Token($this->_parseResponse($response));
        }
        throw new Exception(sprintf('Invalid response status %d', $response->getStatusCode()));
    }
}