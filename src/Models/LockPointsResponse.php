<?php

declare(strict_types=1);

namespace PlacetoPay\Models;

/**
 * Class LockPointsResponse.
 */
class LockPointsResponse extends PlaceToPayResponse
{
    private $miles;
    private $document_id;

    /**
     * LockPointsResponse constructor.
     *
     * @param $payload
     * @param null $errorMessage
     * @param null $errorCode
     */
    public function __construct($payload, $errorMessage = null, $errorCode = null)
    {
        if ($payload != null) {
            $parsedData = json_decode($payload, true);
            if (isset($parsedData['Data']['miles']) && isset($parsedData['Data']['document_id'])) {
                $this->miles = $parsedData['Data']['miles'];
                $this->document_id = $parsedData['Data']['document_id'];
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
    public function getDocumentId()
    {
        return $this->document_id;
    }
}
