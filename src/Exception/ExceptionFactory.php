<?php

declare(strict_types=1);

namespace PlacetoPay\Exception;

class ExceptionFactory
{
    public static function buildException($body): PlaceToPayException
    {
        if (isset($body)) {
            $errorData = json_decode($body, true);
            if ((isset($errorData['Data']['error']) && isset($errorData['Message']))) {
                return ExceptionFactory::buildTransactionExceptions($errorData['Data']['error'], $errorData['Message']);
            } elseif (isset($errorData['error']) && isset($errorData['error_description'])) {
                return new PlaceToPayException($errorData['error'], $errorData['error_description']);
            }
        }

        return  new PlaceToPayException();
    }

    public static function buildTransactionExceptions($code, $message)
    {
        switch ($code) {
            case 'NOT_VALID_TOKEN':
            case 'NOT_VALID_DOCUMENT_ID':
                return new NotValidTokenException($code, $message);
            case 'EXPIRED_TOKEN':
                return new ExpiredTokenException($code, $message);
            case 'INCORRECT_VALUE':
                return new IncorrectValueException($code, $message);
            case 'INSUFFICIENT_BALANCE':
                return new InsufficientBalanceException($code, $message);
            case 'UNAUTHORIZED':
                return new UnauthorizedException($code, $message);
        }
    }
}
