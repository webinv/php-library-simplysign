<?php
/**
 * SimplySign Xades Atom Client
 */

namespace Webinv\SimplySign\Model\Xades\Signing;

/**
 * Class Attachment
 *
 * @package Webinv\SimplySign\Model\Xades\Signing
 * @author <li-on@wp.pl>
 */
class Attachment 
{
    /**
     * @var mixed
     */
    private $file;

    /**
     * @var string
     */
    private $fileName;

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

}
