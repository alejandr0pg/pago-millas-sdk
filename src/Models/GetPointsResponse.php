<?php

declare(strict_types=1);

namespace PlacetoPay\Models;

/**
 * Class GetPointsResponse.
 */
class GetPointsResponse extends PlaceToPayResponse
{
    private $miles;
    private $index_conversion;

    /**
     * GetPointsResponse constructor.
     *
     * @param $payload
     * @param null $errorMessage
     * @param null $errorCode
     */
    public function __construct($payload, $errorMessage = null, $errorCode = null)
    {
        $parsedData = json_decode($payload, true);
        $this->miles = $parsedData['Data']['miles'];
        $this->index_conversion = $parsedData['Data']['index_conversion'];

        parent::__construct($parsedData['Message'], $errorMessage, $errorCode);
    }

    /**
     * @return mixed
     */
    public function getMiles()
    {
        return $this->miles;
    }

    /**
     * @return mixed
     */
    public function getIndexOfConversion()
    {
        return $this->index_conversion;
    }
}
