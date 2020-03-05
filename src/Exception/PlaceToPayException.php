<?php

declare(strict_types=1);

namespace PlacetoPay\Exception;

use Exception;

class PlaceToPayException extends Exception
{
    private $errorCode;
    public function __construct($errorCode = null, $message = null)
    {
        $this->errorCode = $errorCode;
        parent::__construct($message);
    }

    public function getErrorCode()
    {
        return $this->errorCode;
    }
}
