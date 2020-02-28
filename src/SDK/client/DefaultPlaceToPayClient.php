<?php

declare(strict_types=1);

namespace PlaceToPay\SDK\Client;

use PlaceToPay\SDK\HTTPClient\Interfaces\HTTPClient;

class DefaultPlaceToPayClient extends AbstractPlaceToPayClient
{

    public function __construct(
        HTTPClient $httpClient,
        string $apiUrl,
        string $redirectUrl,
        string $clientId,
        string $clientSecret
    ) {
        parent::__construct(
            $httpClient,
            $apiUrl,
            $redirectUrl,
            $clientId,
            $clientSecret
        );
    }
}
