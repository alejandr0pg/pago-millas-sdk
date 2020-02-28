<?php

declare(strict_types=1);

namespace PlaceToPay\SDK\Models;

class PlaceToPayResponse
{
    private  $data;
    private  $message;

    public function __construct($payload)
    {
        $parsedData = json_decode($payload, true);
        $this->data = $parsedData['Data'];
        $this->message = $parsedData['Message'];
    }

    public function getData()
    {
        return $this->data;
    }

    public function getMessage()
    {
        return $this->message;
    }
}
