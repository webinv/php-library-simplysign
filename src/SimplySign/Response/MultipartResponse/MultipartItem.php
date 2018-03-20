<?php
/**
 * SimplySign WebService Client
 */

namespace SimplySign\Response\MultipartResponse;

/**
 * Class MultipartItem
 * @package SimplySign\Response\MultipartResponse
 * @author Krzysztof Kardasz <krzysztof@kardasz.eu>
 */
class MultipartItem
{
    /**
     * @var array
     */
    protected $headers = [];

    /**
     * @var string
     */
    protected $body;

    /**
     * @var array
     */
    private $contentTypeData;

    /**
     * @var array
     */
    private $contentDispositionData;

    /**
     * @var string
     */
    private $name;

    /**
     * MultipartItem constructor.
     * @param array $headers
     * @param string $body
     */
    public function __construct(array $headers = [], $body = '')
    {
        $this->headers = $headers;
        $this->body = $body;
    }

    public function getName()
    {
        if (null === $this->name) {
            $options = $this->getContentDispositionOptions();
            if (!isset($options['name'])) {
                throw new MultipartResponseException(
                    'Content-Disposition name is missing'
                );
            }

            $this->name = $options['name'];
        }

        return $this->name;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasHeader($name)
    {
        foreach ($this->headers as $key => $value) {
            if (0 === strcasecmp($name, $key)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $name
     * @return null
     */
    public function getHeader($name)
    {
        foreach ($this->headers as $key => $value) {
            if (0 === strcasecmp($name, $key)) {
                return $this->headers[$key];
            }
        }

        return null;
    }

    /**
     * @param $name
     * @return null|string
     */
    public function getHeaderLine($name)
    {
        $headers = $this->getHeader($name);
        if (null !== $headers) {
            return implode(',', $headers);
        }

        return '';
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return (string)$this->body;
    }

    /**
     * @param $content
     * @return MultipartItem
     * @throws MultipartResponseException
     */
    public static function fromString($content)
    {
        $chunks = preg_split('/(\r?\n){2}/', $content, 2);
        if (count($chunks) < 2) {
            throw new MultipartResponseException(
                'Invalid  content part'
            );
        }

        $currentHeader = '';
        $headers = [];
        foreach (preg_split('/\r?\n/', trim($chunks[0])) as $line) {
            if (empty($line)) {
                continue;
            }

            // Multi line header
            if (preg_match('/^\h+(.+)/', $line, $matches)) {
                $currentHeader .= ' ' . $matches[1];
            } else {
                if (!empty($currentHeader)) {
                    $parts = explode(':', $currentHeader, 2);
                    $name = strtolower(trim($parts[0]));
                    $value = (2 === count($parts)) ? trim($parts[1]) : '';
                    $headers[$name][] = $value;
                }
                $currentHeader = trim($line);
            }
        }

        if (!empty($currentHeader)) {
            $parts = explode(':', $currentHeader, 2);
            $name = strtolower(trim($parts[0]));
            $value = (2 === count($parts)) ? trim($parts[1]) : '';
            $headers[$name][] = $value;
        }

        return new MultipartItem($headers, trim($chunks[1]));
    }

    /**
     * @return mixed|null
     */
    public function getContentType()
    {
        $data = $this->getContentTypeData();
        if (isset($data['value'])) {
            return $data['value'];
        }
        return '';
    }

    /**
     * @return mixed|null
     */
    public function getContentTypeOptions()
    {
        $data = $this->getContentTypeData();
        if (isset($data['options'])) {
            return $data['options'];
        }
        return [];
    }

    /**
     * @return array
     */
    private function getContentTypeData()
    {
        if (null === $this->contentTypeData) {
            $this->contentTypeData = $this->parseHeader($this->getHeaderLine('Content-type'));
        }
        return $this->contentTypeData;
    }

    /**
     * @return mixed|null
     */
    public function getContentDisposition()
    {
        $data = $this->getContentDispositionData();
        if (isset($data['value'])) {
            return $data['value'];
        }
        return '';
    }

    /**
     * @return mixed|null
     */
    public function getContentDispositionOptions()
    {
        $data = $this->getContentDispositionData();
        if (isset($data['options'])) {
            return $data['options'];
        }
        return [];
    }

    /**
     * @return array
     */
    private function getContentDispositionData()
    {
        if (null === $this->contentDispositionData) {
            $this->contentDispositionData = $this->parseHeader($this->getHeaderLine('Content-Disposition'));
        }
        return $this->contentDispositionData;
    }

    /**
     * @param $header
     * @return array
     */
    private function parseHeader($header)
    {
        $parts = explode(';', trim($header));
        $headerValue = array_shift($parts);
        $options = [];

        foreach ($parts as $part) {
            if (!empty($part)) {
                $chunks = explode('=', trim($part), 2);
                if (2 === count($chunks)) {
                    $options[trim($chunks[0])] = trim($chunks[1], ' "');
                } else {
                    $options[$chunks[0]] = '';
                }
            }
        }

        return ['value' => $headerValue, 'options' => $options];
    }

    public function __toString()
    {
        return $this->getBody();
    }
}
