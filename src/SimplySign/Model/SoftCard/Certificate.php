<?php
/**
 * SimplySign WebService Client
 */

namespace SimplySign\Model\SoftCard;

/**
 * Class Certificate
 * @package SimplySign\Model\SoftCard
 * @author Krzysztof Kardasz <krzysztof@kardasz.eu>
 */
class Certificate
{
    /**
     * @var array
     */
    protected $details = [];

    /**
     * @var string
     */
    protected $content;

    const FIELD_ISSUER = 'issuer';
    const FIELD_SUBJECT = 'subject';
    const FIELD_LEVEL = 'level';
    const FIELD_FINGERPRINT = 'fingerprint';
    const FIELD_SERIALNO = 'serialno';
    const FIELD_NOTBEFORE = 'notbefore';
    const FIELD_NOTAFTER = 'notafter';
    const FIELD_KEYUSAGE = 'keyusage';
    const FIELD_EXTENDEDKEYUSAGE = 'extendedkeyusage';
    const FIELD_BASICCONSTRAINTS = 'basicconstraints';
    const FIELD_FILENAME = 'filename';

    /**
     * Certificate constructor.
     * @param array $details
     * @param $content
     */
    public function __construct(array $details = [], $content)
    {
        $this->details = $details;
        $this->content = $content;
    }

    /**
     * @return array
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasDetails($name)
    {
        return isset($this->details[$name]);
    }

    /**
     * @param $name
     * @return mixed|null
     */
    public function getDetail($name)
    {
        if (isset($this->details[$name])) {
            return $this->details[$name];
        }
        return null;
    }

    /**
     * @return mixed|null
     */
    public function getIssuer()
    {
        return $this->getDetail(self::FIELD_ISSUER);
    }

    /**
     * @return mixed|null
     */
    public function getSubject()
    {
        return $this->getDetail(self::FIELD_SUBJECT);
    }

    /**
     * @return mixed|null
     */
    public function getLevel()
    {
        return $this->getDetail(self::FIELD_LEVEL);
    }

    /**
     * @return mixed|null
     */
    public function getFingerprint()
    {
        return $this->getDetail(self::FIELD_FINGERPRINT);
    }

    /**
     * @return mixed|null
     */
    public function getFilename()
    {
        return $this->getDetail(self::FIELD_FILENAME);
    }

    /**
     * @return \DateTime
     */
    public function getExpiration()
    {
        return new \DateTime($this->getDetail(self::FIELD_NOTAFTER));
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
}
