<?php

declare(strict_types=1);

namespace PlaceToPay\SDK\Client;

use DateInterval;
use DateTime;
use PlaceToPay\SDK\HTTPClient\Interfaces\HTTPClient;
use PlaceToPay\SDK\Client\Interfaces\PlaceToPayClient;
use PlaceToPay\SDK\Exception\ExceptionFactory;
use PlaceToPay\SDK\Exception\PlaceToPayException;
use PlaceToPay\SDK\HTTPClient\GuzzleHttpClient;
use PlaceToPay\SDK\Models\PlaceToPayResponse;
use PlaceToPay\SDK\HTTPClient\Enums\HTTPClientType;

class AbstractPlaceToPayClient implements PlaceToPayClient
{


    protected  string $api_url;
    protected  string $redirect_url;

    protected string $client_id;
    protected string $client_secret;

    protected  HTTPClient $http_client;
    protected  int $client_type;


    protected string $authToken;
    protected string $refreshToken;
    protected string $expireAt;

    protected function __construct(
        HTTPClient $http_client,
        string $api_url,
        string $redirect_url,
        string $client_id,
        string $client_secret
    ) {
        $this->api_url = $api_url;
        $this->redirect_url = $redirect_url;
        $this->http_client = $http_client;
        $this->init($client_id, $client_secret);
    }


    private function init(
        string $client_id,
        string $client_secret
    ) {

        if (session_status() == PHP_SESSION_ACTIVE) {
            $this->loadSessionData();
        } else {
            $this->getBearerToken($client_id, $client_secret);
        }
    }

    private function getBearerToken(string $client_id, string $client_secret): void
    {
        $url = "{$this->api_url}/oauth2/token?grant_type=authorization_code&client_id={$client_id}
        &client_secret={$client_secret}&scope=write&redirect_uri={$this->redirect_url}";

        $response = $this->http_client->get($url, []);

        $this->storeToken(json_decode($response->getBody(), true));
    }


    public function getPoints(string $merchantId): PlaceToPayResponse
    {
        $url = "{$this->api_url}/getPoints?merchant_id={$merchantId}";
        $headers = $this->buildHeaders();

        $response = $this->http_client->get($url, $headers);

        $result = new PlaceToPayResponse($response->getBody());

        return $result;
    }

    public function lockPoints(int $points): PlaceToPayResponse
    {
        $url = "{$this->api_url}/lockPoints";
        $headers = $this->buildHeaders();
        $body = [
            'points' => $points
        ];

        $response = $this->http_client->post($url, $headers, $body);

        $result = new PlaceToPayResponse($response->getBody());

        return $result;
    }

    public function debitPoints(int $documentId): PlaceToPayResponse
    {
        $url = "{$this->api_url}/debitPoints";
        $headers = $this->buildHeaders();
        $body = [
            'document_id' => $documentId
        ];

        $response = $this->http_client->post($url, $headers, $body);

        $result = new PlaceToPayResponse($response->getBody());

        return $result;
    }

    public function cancelTransaction(int $documentId): PlaceToPayResponse
    {
        $url = "{$this->api_url}/cancelTransaction";
        $headers = $this->buildHeaders();
        $body = [
            'document_id' => $documentId
        ];
        $response = $this->http_client->post($url, $headers, $body);
        $result = new PlaceToPayResponse($response->getBody());
        return $result;
    }

    public function reversePoint(int $documentId): PlaceToPayResponse
    {
        $url = "{$this->api_url}/reversePoint";
        $headers = $this->buildHeaders();
        $body = [
            'document_id' => $documentId
        ];

        $response = $this->http_client->post($url, $headers, $body);
        $result = new PlaceToPayResponse($response->getBody());
        return $result;
    }

    public function lockAndDebitPoints(int $points): PlaceToPayResponse
    {
        $lockPointResult = $this->lockPoints($points);
        return $this->debitPoints($lockPointResult->getData()['document_id']);
    }

    protected function buildHeaders(): array
    {

        if (!isset($this->authToken)) {
            throw new PlaceToPayException(401, "Missing access token");
        }

        if ($this->tokenExpired()) {
            $this->refreshToken();
        }

        return [
            "Content-Type" => "application/json",
            "Accept" => "application/json",
            "Authorization" => "Bearer {$this->authToken}"
        ];
    }


    protected function tokenExpired(): bool
    {
        return $this->expireAt <= new DateTime();
    }

    protected function refreshToken(): void
    {
        $this->getBearerToken($this->client_id, $this->client_secret);
    }

    protected function storeToken($result): void
    {
        $this->authToken = $result['access_token'];
        $this->refreshToken = $result['refresh_token'];
        $date = new DateTime();
        $this->expireAt  = $date->add(new DateInterval("PT{$result['refresh_token']}S"))->getTimestamp();

        session_start();

        $_SESSION["access_token"] = $this->authToken;
        $_SESSION["refresh_token"] = $this->refreshToken;
        $_SESSION["expire_at"] = $this->expireAt;
        $_SESSION["CLIENT_ID"] = $this->client_id;
        $_SESSION["CLIENT_SECRET"] = $this->client_secret;
    }

    private function loadSessionData(): void
    {
        $this->authToken = $_SESSION["access_token"];
        $this->refreshToken =   $_SESSION["refresh_token"];
        $this->expireAt =    $_SESSION["expire_at"];
        $this->client_id =    $_SESSION["CLIENT_ID"];
        $this->client_secret =    $_SESSION["CLIENT_SECRET"];
    }
}
