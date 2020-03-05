<?php

declare(strict_types=1);

namespace PlacetoPay\Exception;

use Exception;

/**
 * Class PlaceToPayException.
 */
class PlaceToPayException extends Exception
{
    private $errorCode;

    /**
     * PlaceToPayException constructor.
     *
     * @param null $errorCode
     * @param null $message
     */
    public function __construct($errorCode = null, $message = null)
    {
        $this->errorCode = $errorCode;

        parent::__construct($message);
    }

    /**
     * @return string|null
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }
}
