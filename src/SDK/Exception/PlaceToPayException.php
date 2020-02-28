<?php

declare(strict_types=1);

namespace PlaceToPay\SDK\Exception;

use Exception;

class PlaceToPayException extends Exception
{
    public function __construct($code = null, string $message = null)
    {
        $this->code = strval($code);
        parent::__construct($message);
    }
}
