<?php

declare(strict_types=1);

namespace PlacetoPay\Models;

class LockPointsResponse extends PlaceToPayResponse
{
    private $miles;
    private $document_id;

    public function __construct($payload, $errorMessage = null, $errorCode = null)
    {
        $parsedData = json_decode($payload, true);
        $this->miles = $parsedData['Data']['miles'];
        $this->document_id = $parsedData['Data']['document_id'];

        parent::__construct($parsedData['Message'], $errorMessage, $errorCode);
    }

    public function getMiles()
    {
        return $this->miles;
    }

    public function getDocumentId()
    {
        return $this->document_id;
    }
}
