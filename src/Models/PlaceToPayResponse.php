<?php

declare(strict_types=1);

namespace PlacetoPay\Models;

class PlaceToPayResponse
{

    private $message;
    private $errorMessage;
    private $errorCode;
    private $isSuccess;

    public function __construct($message = null, $errorMessage = null, $errorCode = null)
    {
        $this->message = $message;
        $this->errorMessage = $errorMessage;
        $this->errorCode = $errorCode;

        if ($this->errorCode != null || $this->errorMessage != null) {
            $this->isSuccess = false;
        }
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    public function getErrorCode()
    {
        return $this->errorCode;
    }

    public function isSuccessful()
    {
        return $this->isSuccess;
    }
}
