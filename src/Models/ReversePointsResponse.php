<?php

declare(strict_types=1);

namespace PlacetoPay\Models;

class ReversePointsResponse extends PlaceToPayResponse
{
    public function __construct($message = null, $errorMessage = null, $errorCode = null)
    {
        parent::__construct($message, $errorMessage, $errorCode);
    }
}
