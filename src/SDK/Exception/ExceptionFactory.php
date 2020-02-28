<?php

declare(strict_types=1);

namespace PlaceToPay\SDK\Exception;


class ExceptionFactory
{

    public static function buildException($code, $body): PlaceToPayException
    {
        $error = json_decode($body, true);
        return new PlaceToPayException($code, $error['error_description']);
    }
}
