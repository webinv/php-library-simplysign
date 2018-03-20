<?php
/**
 * SimplySign WebService Client
 */

namespace SimplySign;

use Throwable;

/**
 * Class Exception
 *
 * @package SimplySign
 * @author Krzysztof Kardasz <krzysztof@kardasz.eu>
 */
class Exception extends \Exception
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * Exception constructor.
     *
     * @param string $message
     * @param int $code
     * @param array $data
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, array $data = [], Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}
