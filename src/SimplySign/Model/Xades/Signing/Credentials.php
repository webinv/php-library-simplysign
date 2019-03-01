<?php
/**
 * SimplySign Xades Atom Client
 */

namespace Webinv\SimplySign\Model\Xades\Signing;

/**
 * Class Credentials
 *
 * @package Webinv\SimplySign\Model\Xades\Signing
 * @author <li-on@wp.pl>
 */
class Credentials implements \JsonSerializable
{
    /**
     * @var string
     */
    private $card;

    /**
     * @var string
     */
    private $pin;

    /**
     * @var string
     */
    private $token;

    /**
     * @return mixed
     */
    public function getCard()
    {
        return $this->card;
    }

    /**
     * @param $card
     * @return $this
     */
    public function setCard($card)
    {
        $this->card = $card;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPin()
    {
        return $this->pin;
    }

    /**
     * @param $pin
     * @return $this
     */
    public function setPin($pin)
    {
        $this->pin = $pin;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param $token
     * @return $this
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
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
        $data = [];

        if (null !== $this->getCard()) {
            $data['card'] = $this->getCard();
        }

        if (null !== $this->getPin()) {
            $data['pin'] = bin2hex($this->getPin());
        }

        /*if (null !== $this->getToken()) {
            $data['token'] = $this->getToken();
        }*/

        return $data;
    }
}
