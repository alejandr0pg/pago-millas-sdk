<?php

declare(strict_types=1);

namespace PlacetoPay\Client;

/**
 * Class DefaultPlaceToPayClient.
 */
class DefaultPlaceToPayClient extends AbstractPlaceToPayClient
{
    /**
     * DefaultPlaceToPayClient constructor.
     *
     * @param $httpClient
     * @param $apiUrl
     * @param $redirectUrl
     * @param $clientId
     * @param $clientSecret
     * @param $cache
     */
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
