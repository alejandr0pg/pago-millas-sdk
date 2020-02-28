<?php

declare(strict_types=1);

namespace PlaceToPay\SDK\Client;

use PlaceToPay\SDK\Client\Interfaces\PlaceToPayClient;
use PlaceToPay\SDK\Exception\PlaceToPayException;
use PlaceToPay\SDK\HTTPClient\Enums\HTTPClientType;
use PlaceToPay\SDK\HTTPClient\GuzzleHttpClient;
use PlaceToPay\SDK\HTTPClient\Interfaces\HTTPClient;

final class PlaceToPayClientBuilder
{

    public static function builder()
    {
        return new ClientBuilder();
    }
}
class ClientBuilder
{

    private HTTPClient $http_client;
    private string $api_url;
    private string $client_id;
    private string $client_secret;
    private string $redirect_url;

    function __construct()
    {
    }

    public function withHTTPClient(int $clientType): ClientBuilder
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

    public function withApiUrl(string $apiUrl): ClientBuilder
    {
        $this->api_url = $apiUrl;
        return $this;
    }

    public function withRedirectUrl(string $redirectUrl): ClientBuilder
    {
        $this->redirect_url = $redirectUrl;
        return $this;
    }

    public function withClientId(string $clientId): ClientBuilder
    {
        $this->client_id = $clientId;
        return $this;
    }

    public function withClientSecret(string $clientSecret): ClientBuilder
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


        $client = new DefaultPlaceToPayClient(
            $this->http_client,
            $this->api_url,
            $this->redirect_url,
            $this->client_id,
            $this->client_secret
        );

        return $client;
    }
}
