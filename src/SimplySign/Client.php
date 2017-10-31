<?php

namespace SimplySign;

use Psr\Http\Message\ResponseInterface;

/**
 * Class Client
 *
 * @package SimplySign
 * @author Krzysztof Kardasz <krzysztof@kardasz.eu>
 */
abstract class Client
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * Authorization constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @param ResponseInterface $response
     * @return mixed
     * @throws Exception
     */
    protected function _parseResponse (ResponseInterface $response)
    {
        $body = (string)$response->getBody();
        if (!empty($body)) {
            $json = json_decode($body, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                return $json;
            }

            throw new Exception(
                sprintf('Invalid JSON data: %s', $body)
            );
        }
    }
}