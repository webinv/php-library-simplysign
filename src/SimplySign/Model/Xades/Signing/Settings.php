<?php
/**
 * SimplySign Xades Atom Client
 */

namespace Webinv\SimplySign\Model\Xades\Signing;

/**
 * Class Settings
 *
 * @package Webinv\SimplySign\Model\Xades\Signing
 * @author <li-on@wp.pl>
 */
class Settings implements \JsonSerializable
{
    
    /* templates - default settings*/
    //not supported yet
    //const TEMPLATE_DETACHED = 'xades-bes-detached';
    //const TEMPLATE_T_DETACHED = 'xades-t-detached';
    //const TEMPLATE_TQ_DETACHED = 'xades-tq-detached';
    
    const TEMPLATE_ENVELOPED = 'xades-bes-enveloped';
    const TEMPLATE_T_ENVELOPED = 'xades-t-enveloped';
    const TEMPLATE_TQ_ENVELOPED = 'xades-tq-enveloped';
    
    const TEMPLATE_ENVELOPING = 'xades-bes-enveloping';
    const TEMPLATE_T_ENVELOPING = 'xades-t-enveloping';
    const TEMPLATE_TQ_ENVELOPING = 'xades-tq-enveloping';
    
    const TEMPLATE_E_ZLA = 'e-zla';
    
    /* profiles */
    const PROFILE_BES = 'xades-bes';
    const PROFILE_T = 'xades-bes';
    
    /* transform types */
    //const TRANSFORM_DETACHED = 'detached';
    const TRANSFORM_ENVELOPED = 'enveloped';
    const TRANSFORM_ENVELOPING = 'enveloping';
    
    
    /**
     * @var string
     */
    private $template;
    
    /**
     * @var string
     */
    private $profile;

    /**
     * @var string
     */
    private $transformType;

    /**
     * @var string $singatureMethod "rsa_sha1", "rsa_sha224", "rsa_sha256", "rsa_sha384", "rsa_sha512", "rsa_RIPEMD160"
     */
    private $singatureMethod;
    
    
    /**
     * @var string $digestMethod "sha1", "sha224", "sha256", "sha384", "sha512", "RIPEMD160"
     */
    private $digestMethod;   
    
    /**
     * @var string $canonicalizationMethod "inclusive", "inclusive_with_comments", "exclusive", "exclusive_with_comments"
     */
    private $canonicalizationMethod;  

    
    /**
     * @var Timestamp
     */
    private $timestamp;

    public function getTemplate(){
		return $this->template;
	}

	public function setTemplate($template){
		$this->template = $template;
        return $this;
	}

	public function getProfile(){
		return $this->profile;
	}

	public function setProfile($profile){
		$this->profile = $profile;
        return $this;
	}

	public function getTransformType(){
		return $this->transformType;
	}

	public function setTransformType($transformType){
		$this->transformType = $transformType;
        return $this;
	}

	public function getSingatureMethod(){
		return $this->singatureMethod;
	}

	public function setSingatureMethod($singatureMethod){
		$this->singatureMethod = $singatureMethod;
        return $this;
	}

	public function getDigestMethod(){
		return $this->digestMethod;
	}

	public function setDigestMethod($digestMethod){
		$this->digestMethod = $digestMethod;
        return $this;
	}

	public function getCanonicalizationMethod(){
		return $this->canonicalizationMethod;
	}

	public function setCanonicalizationMethod($canonicalizationMethod){
		$this->canonicalizationMethod = $canonicalizationMethod;
        return $this;
	}

	public function getTimestamp(){
		return $this->timestamp;
	}

	public function setTimestamp($timestamp){
		$this->timestamp = $timestamp;
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

        if (null !== $this->template) {
            $data['template'] = $this->template;
        }
        
        if (null !== $this->profile) {
            $data['profile'] = $this->profile;
        }else{
            if (null == $this->template) {
                $data['profile'] = Settings::PROFILE_BES;
            }
        }

        if (null !== $this->transformType) {
            $data['transformType'] = $this->transformType;
        }

        if (null !== $this->singatureMethod) {
            $data['singatureMethod'] = $this->singatureMethod;
        }
        
        if (null !== $this->digestMethod) {
            $data['digestMethod'] = $this->digestMethod;
        }
        
        if (null !== $this->canonicalizationMethod) {
            $data['canonicalizationMethod'] = $this->canonicalizationMethod;
        }
        
        if (null !== $this->timestamp) {
            $data['timestamp'] = $this->timestamp;
        }

        return $data;
    }
}
