<?php

declare(strict_types=1);

namespace PlacetoPay\Models;

/**
 * Class PlaceToPayResponse.
 */
class PlaceToPayResponse
{
    private $message;
    private $errorMessage;
    private $errorCode;
    private $isSuccess = true;

    /**
     * PlaceToPayResponse constructor.
     *
     * @param null $message
     * @param null $errorMessage
     * @param null $errorCode
     */
    public function __construct($message = null, $errorMessage = null, $errorCode = null)
    {
        $this->message = $message;
        $this->errorMessage = $errorMessage;
        $this->errorCode = $errorCode;

        if (isset($this->errorCode) || isset($this->errorMessage)) {
            $this->isSuccess = false;
        }
    }

    /**
     * @return null
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return null
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * @return null
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return $this->isSuccess;
    }
}
