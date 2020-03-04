<?php

declare(strict_types=1);

namespace PlacetoPay\Models;

class GetPointsResponse extends PlaceToPayResponse
{

    private $miles;
    private $index_conversion;

    public function __construct($payload, $errorMessage = null, $errorCode = null)
    {
        $parsedData = json_decode($payload, true);
        $this->miles = $parsedData['Data']['miles'];
        $this->index_conversion = $parsedData['Data']['index_conversion'];
        parent::__construct($parsedData['Message'], $errorMessage, $errorCode);
    }

    public function getMiles()
    {
        return $this->miles;
    }

    public function getIndexOfConversion()
    {
        return $this->index_conversion;
    }
}
