<?php

declare(strict_types=1);

namespace PlacetoPay\Exception;

/**
 * Class ExceptionFactory.
 */
class ExceptionFactory
{
    /**
     * @param $body
     * @return PlaceToPayException
     */
    public static function buildException($body): PlaceToPayException
    {
        if (isset($body)) {
            $errorData = json_decode($body, true);

            if ((isset($errorData['Data']['error']) && isset($errorData['Message']))) {
                return self::buildTransactionExceptions($errorData['Data']['error'], $errorData['Message']);
            } elseif (isset($errorData['error']) && isset($errorData['error_description'])) {
                return new PlaceToPayException($errorData['error'], $errorData['error_description']);
            }
        }

        return  new PlaceToPayException();
    }

    /**
     * @param $code
     * @param $message
     * @return \Exception|ExpiredTokenException|IncorrectValueException|InsufficientBalanceException|NotValidTokenException|UnauthorizedException
     */
    public static function buildTransactionExceptions($code, $message)
    {
        switch ($code) {
            case 'NOT_VALID_TOKEN':
                return new NotValidTokenException($code, $message);
            case 'NOT_VALID_DOCUMENT_ID':
                return new NotValidDocumentIdException($code, $message);
            case 'EXPIRED_TOKEN':
                return new ExpiredTokenException($code, $message);
            case 'INCORRECT_VALUE':
                return new IncorrectValueException($code, $message);
            case 'INSUFFICIENT_BALANCE':
                return new InsufficientBalanceException($code, $message);
            case 'UNAUTHORIZED':
                return new UnauthorizedException($code, $message);
            default:
                return new \Exception($code, $message);
        }
    }
}
