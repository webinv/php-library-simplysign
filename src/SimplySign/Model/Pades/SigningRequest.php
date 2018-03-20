<?php
/**
 * SimplySign WebService Client
 */

namespace Webinv\SimplySign\Model\Pades;

use Webinv\SimplySign\Exception;
use Webinv\SimplySign\Model\Pades\Signing\Credentials;

/**
 * Class SigningRequest
 *
 * @package Webinv\SimplySign\Model\Pades
 * @author Krzysztof Kardasz <krzysztof@kardasz.eu>
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
    private $certificate;

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
     * @return string base64 encoded file contents
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param string $file base64 encoded file contents
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

        $this->file = base64_encode(file_get_contents($file));
        return $this;
    }

    /**
     * @param string $file base64 encoded file contents
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

        if (null !== $this->getCredentials()) {
            $data['credentials'] = $this->getCredentials();
        }

        if (null !== $this->getFile()) {
            $data['file'] = $this->getFile();
        }

        if (null !== $this->getCertificate()) {
            $data['certificate'] = $this->getCertificate();
        }

        return $data;
    }
}
