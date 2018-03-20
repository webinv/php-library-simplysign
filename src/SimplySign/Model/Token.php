<?php
/**
 * SimplySign WebService Client
 */

namespace SimplySign\Model;

/**
 * Class AccessToken
 *
 * @package SimplySign\Model
 * @author Krzysztof Kardasz <krzysztof@kardasz.eu>
 */
class Token implements \JsonSerializable
{
    /**
     * @var string
     */
    private $accessToken;

    /**
     * @var string
     */
    private $tokenType;

    /**
     * @var string
     */
    private $expires;

    /**
     * @var string
     */
    private $refreshToken;

    /**
     * Token constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        if (isset($data['access_token'])) {
            $this->setAccessToken($data['access_token']);
        }

        if (isset($data['token_type'])) {
            $this->setTokenType($data['token_type']);
        }

        if (isset($data['expires_in'])) {
            $this->setExpires(date(\DateTime::ATOM, time()+(int)$data['expires_in']));
        } elseif (isset($data['expires'])) {
            $this->setExpires(date(\DateTime::ATOM, strtotime($data['expires'])));
        }

        if (isset($data['refresh_token'])) {
            $this->setRefreshToken($data['refresh_token']);
        }
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @param string $accessToken
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * @return string
     */
    public function getTokenType()
    {
        return $this->tokenType;
    }

    /**
     * @param string $tokenType
     */
    public function setTokenType($tokenType)
    {
        $this->tokenType = $tokenType;
    }

    /**
     * @return string
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * @param string $expires
     */
    public function setExpires($expires)
    {
        $this->expires = $expires;
    }

    /**
     * @return bool
     */
    public function isExpired()
    {
        return time() > strtotime($this->getExpires());
    }

    /**
     * @return string
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    /**
     * @param string $refreshToken
     */
    public function setRefreshToken($refreshToken)
    {
        $this->refreshToken = $refreshToken;
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'token_type' => $this->getTokenType(),
            'access_token' => $this->getAccessToken(),
            'refresh_token' => $this->getRefreshToken(),
            'expires' => $this->getExpires()
        ];
    }
}
