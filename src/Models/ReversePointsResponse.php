<?php

declare(strict_types=1);

namespace PlacetoPay\Models;

/**
 * Class ReversePointsResponse.
 */
class ReversePointsResponse extends PlaceToPayResponse
{
    /**
     * ReversePointsResponse constructor.
     *
     * @param null $message
     * @param null $errorMessage
     * @param null $errorCode
     */
    public function __construct($message = null, $errorMessage = null, $errorCode = null)
    {
        parent::__construct($message, $errorMessage, $errorCode);
    }
}
