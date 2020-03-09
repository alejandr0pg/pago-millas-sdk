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
        if ($payload != null) {
            $parsedData = json_decode($payload, true);
            if (isset($parsedData['Data']['miles']) && isset($parsedData['Data']['index_conversion'])) {
                $this->miles = $parsedData['Data']['miles'];
                $this->index_conversion = $parsedData['Data']['index_conversion'];
            }
            parent::__construct($parsedData['Message'], $errorMessage, $errorCode);
        } else {
            parent::__construct(null, $errorMessage, $errorCode);
        }
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
