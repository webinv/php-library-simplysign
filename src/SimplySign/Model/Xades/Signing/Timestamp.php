<?php
/**
 * SimplySign Xades Atom Client
 */

namespace Webinv\SimplySign\Model\Xades\Signing;

/**
 * Class Timestamp
 *
 * @package Webinv\SimplySign\Model\Xades\Signing
 * @author <li-on@wp.pl>
 */
class Timestamp implements \JsonSerializable
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $policy;

    /**
     * @var string
     */
    private $algorithm;

    public function getUrl(){
		return $this->url;
	}

	public function setUrl($url){
		$this->url = $url;
        return $this;
	}

	public function getPolicy(){
		return $this->policy;
	}

	public function setPolicy($policy){
		$this->policy = $policy;
        return $this;
	}

	public function getAlgorithm(){
		return $this->algorithm;
	}

	public function setAlgorithm($algorithm){
		$this->algorithm = $algorithm;
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

        if (null !== $this->url) {
            $data['remote'] = [ 
                    'url' => $this->url
                ]
            if (null !== $this->policy) {
                $data['remote']['policy'] = $this->policy;
            }
        }

        if (null !== $this->algorithm) {
            $data['algorithm'] = $this->algorithm;
        }

        return $data;
    }
}
