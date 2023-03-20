<?php
/**
 * SimplySign Xades Atom Client
 */

namespace Webinv\SimplySign\Model\Xades;

use Webinv\SimplySign\Exception;
use Webinv\SimplySign\Model\Xades\Signing\Credentials;

/**
 * Class SigningRequest
 *
 * @package Webinv\SimplySign\Model\Xades
 * @author <li-on@wp.pl>
 */
class SigningRequest implements \JsonSerializable
{
    /**
     * @var Credentials
     */
    private $credentials;

    /**
     * @var string
     */
    private $file;
    
    /**
     * @var string
     */
    private $fileName;

    /**
     * @var string
     */
    private $certificate;

    /**
     * @var Settings
     */
    private $settings;
    
    /**
     * @var Attachments
     */
    private $attachments=array();

    private array $hashes;

    public function getHashes(): array
    {
        return $this->hashes;
    }

    public function setHashes(array $hashes)
    {
        $this->hashes = $hashes;
    }
    
    /**
     * @return Credentials
     */
    public function getCredentials()
    {
        return $this->credentials;
    }

    /**
     * @param Credentials $credentials
     */
    public function setCredentials(Credentials $credentials)
    {
        $this->credentials = $credentials;
    }

    /**
     * @return string file contents
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param string $file file contents
     * @return $this
     * @throws Exception
     */
    public function setFile($file)
    {
        if (!is_file($file)) {
            throw new Exception(
                sprintf('File "%s" not exists', $file)
            );
        }

        $this->file = file_get_contents($file);
        $this->fileName = basename($file);
        return $this;
    }
    
    /**
     * @return string file name
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName file name
     * @return $this
     * @throws Exception
     */
    public function setFileName($fileName)
    {
        $this->fileName=$fileName;
        return $this;
    }

    /**
     * @param string $file file contents
     * @return $this
     */
    public function setFileContents($file)
    {
        $this->file = $file;
        return $this;
    }

    /**
     * @return string pem content
     */
    public function getCertificate()
    {
        return $this->certificate;
    }

    /**
     * @param string $certificate content pem content
     * @return $this
     */
    public function setCertificate($certificate)
    {
        $this->certificate = $certificate;
        return $this;
    }

    /**
     * @param $file
     * @return $this
     * @throws Exception
     */
    public function setCertificateFile($file)
    {
        if (!is_file($file)) {
            throw new Exception(
                sprintf('File "%s" not exists', $file)
            );
        }
        $this->certificate = file_get_contents($file);
        return $this;
    }
    
    /**
     * @return Settings signature settings
     */
    public function getSettings()
    {
        return $this->certificate;
    }

    /**
     * @param Settings $settings signature settings
     * @return $this
     */
    public function setSettings($settings)
    {
        $this->settings = $settings;
        return $this;
    }
    
    /**
     * @return array of Attachment signature settings
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @param array $attachments  attachments
     * @return $this
     */
    public function setAttachments($attachments)
    {
        $this->attachments = $attachments;
        return $this;
    }
    
    /**
     * Add attachment to internal array of attachments
     * @param Attachment $attachment attachment
     * @return $this
     */
    public function addAttachment($attachment)
    {
        $this->attachments[] = $attachment;
        return $this;
    }
    /**
     * Clear internal array of attachments
     * @return $this
     */
    public function clearAttachments()
    {
        $this->attachments=array();
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

        if (!empty($this->settings)) {
            $data['settings'] = $this->settings;
        }

        if (!empty($this->extra)) {
            $data['extra'] = $this->extra;
        }
        if (!empty($this->credentials)) {
            $data['credentials'] = $this->credentials;
        }

        return $data;
    }
}
