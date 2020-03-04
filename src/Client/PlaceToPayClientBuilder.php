<?php

declare(strict_types=1);

namespace PlacetoPay\Client;

use PlacetoPay\Client\Interfaces\PlaceToPayClient;
use PlacetoPay\Exception\PlaceToPayException;
use PlacetoPay\HTTPClient\Enums\HTTPClientType;
use PlacetoPay\HTTPClient\GuzzleHttpClient;
use PlacetoPay\HTTPClient\Interfaces\HTTPClient;

final class PlaceToPayClientBuilder
{

    public static function builder()
    {
        return new ClientBuilder();
    }
}
class ClientBuilder
{

    private  $http_client;
    private  $api_url;
    private  $client_id;
    private  $client_secret;
    private  $redirect_url;
    // TODO: posiblmente no sea el mejor nombre para esta variable
    private  $cache;

    function __construct()
    {
    }

    public function withHTTPClient($clientType): ClientBuilder
    {
        switch ($clientType) {
            case HTTPClientType::GUZZLE_CLIENT:
                $this->http_client =  new GuzzleHttpClient();
                break;
            default:
                $this->http_client = null;
                break;
        }
        return $this;
    }

    public function withApiUrl($apiUrl): ClientBuilder
    {
        $this->api_url = $apiUrl;
        return $this;
    }

    public function withRedirectUrl($redirectUrl): ClientBuilder
    {
        $this->redirect_url = $redirectUrl;
        return $this;
    }

    public function withCache($cache): ClientBuilder
    {
        $this->cache = $cache;
        return $this;
    }

    public function withClientId($clientId): ClientBuilder
    {
        $this->client_id = $clientId;
        return $this;
    }

    public function withClientSecret($clientSecret): ClientBuilder
    {
        $this->client_secret = $clientSecret;
        return $this;
    }

    public function build(): PlaceToPayClient
    {
        if (!isset($this->http_client)) {
            $this->http_client =  new GuzzleHttpClient();
        }

        if (!isset($this->redirect_url)) {
            throw new PlaceToPayException("you must provide a redirect url");
        }

        if (!isset($this->api_url)) {
            throw new PlaceToPayException("you must provide a api url");
        }

        if (!isset($this->cache)) {
            throw new PlaceToPayException("you must provide a psr6 cache implementation");
        }

        $client = new DefaultPlaceToPayClient(
            $this->http_client,
            $this->api_url,
            $this->redirect_url,
            $this->client_id,
            $this->client_secret,
            $this->cache
        );

        return $client;
    }
}
