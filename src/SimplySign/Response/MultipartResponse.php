<?php
/**
 * SimplySign WebService Client
 */

namespace Webinv\SimplySign\Response;

use Psr\Http\Message\ResponseInterface;
use Webinv\SimplySign\Response\MultipartResponse\MultipartItem;
use Webinv\SimplySign\Response\MultipartResponse\MultipartResponseException;

/**
 * Class MultipartResponse
 * @package Webinv\SimplySign\Response
 * @author Krzysztof Kardasz <krzysztof@kardasz.eu>
 */
class MultipartResponse
{
    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @var boolean
     */
    private $multipart;

    /**
     * @var MultipartItem[]
     */
    private $items;

    /**
     * @var array
     */
    private $contentTypeData;

    /**
     * @var string
     */
    private $boundary;

    /**
     * MultipartResponse constructor.
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * @return bool
     */
    public function isMultipart()
    {
        if (null === $this->multipart) {
            $contentType = $this->response->getHeaderLine('Content-type');
            $this->multipart = 'multipart/form-data' === trim(strstr($contentType, ';', true));
        }

        return $this->multipart;
    }

    /**
     * @return MultipartItem[]
     * @throws MultipartResponseException
     */
    public function getItems()
    {
        if (null === $this->items) {
            $this->items = [];
            $body = (string)$this->response->getBody();
            $delimiter = '--' . preg_quote($this->getBoundary(), '/');

            if (preg_match(sprintf('/%s\r?\n(.+?)\r?\n%s--/s', $delimiter, $delimiter), $body, $matches)) {
                $parts = preg_split(sprintf('/\r?\n%s\r?\n/', $delimiter), $matches[1]);
                foreach ($parts as $content) {
                    $this->items[] = MultipartItem::fromString($content);
                }
            }
        }

        return $this->items;
    }

    /**
     * @param $name
     * @return bool
     * @throws MultipartResponseException
     */
    public function hasItem($name)
    {
        foreach ($this->getItems() as $item) {
            if ($item->getName() == $name) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $name
     * @return MultipartItem[]
     * @throws MultipartResponseException
     */
    public function getItem($name)
    {
        $items = [];

        foreach ($this->getItems() as $item) {
            if ($item->getName() == $name) {
                $items[] = $item;
            }
        }

        return $items;
    }

    /**
     * @return string
     * @throws MultipartResponseException
     */
    private function getBoundary()
    {
        if (null === $this->boundary) {
            $contentTypeData = $this->getContentTypeData();
            if (!isset($contentTypeData['options']['boundary'])) {
                throw new MultipartResponseException(
                    'Boundary header missing'
                );
            }
            $this->boundary = $contentTypeData['options']['boundary'];
        }

        return $this->boundary;
    }

    /**
     * @return array
     */
    private function getContentTypeData()
    {
        if (null === $this->contentTypeData) {
            $contentType = $this->response->getHeaderLine('Content-type');
            $this->contentTypeData = $this->parseHeaderContent($contentType);
        }

        return $this->contentTypeData;
    }

    /**
     * @param $header
     * @return array
     */
    private function parseHeaderContent($header)
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
}
