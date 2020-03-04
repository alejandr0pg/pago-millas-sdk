<?php

declare(strict_types=1);

namespace PlacetoPay\Client;


class DefaultPlaceToPayClient extends AbstractPlaceToPayClient
{

    public function __construct(
        $httpClient,
        $apiUrl,
        $redirectUrl,
        $clientId,
        $clientSecret,
        $cache
    ) {
        parent::__construct(
            $httpClient,
            $apiUrl,
            $redirectUrl,
            $clientId,
            $clientSecret,
            $cache
        );
    }
}
