<?php

declare(strict_types=1);

namespace PlacetoPay\Client;

use Exception;
use PlacetoPay\Client\Interfaces\PlaceToPayClient;
use PlacetoPay\Exception\PlaceToPayException;
use PlacetoPay\Models\CancelTransactionResponse;
use PlacetoPay\Models\DebitPointsResponse;
use PlacetoPay\Models\GetPointsResponse;
use PlacetoPay\Models\LockPointsResponse;
use PlacetoPay\Models\ReversePointResponse;

abstract class AbstractPlaceToPayClient implements PlaceToPayClient
{
    protected $api_url;
    protected $redirect_url;
    protected $client_id;
    protected $client_secret;
    protected $http_client;
    protected $client_type;
    protected $authToken;
    protected $refreshToken;
    protected $expireAt;
    protected $cache;

    protected function __construct(
        $http_client,
        $api_url,
        $redirect_url,
        $client_id,
        $client_secret,
        $cache
    ) {
        $this->api_url = $api_url;
        $this->redirect_url = $redirect_url;
        $this->http_client = $http_client;
        $this->cache = $cache;
        $this->init($client_id, $client_secret);
    }

    private function init(
        $client_id,
        $client_secret
    ) {
        if ($this->tokenExpired()) {
            $this->loadSessionData();
        } else {
            $this->getBearerToken($client_id, $client_secret);
        }
    }

    public function lockAndDebitPoints($points): DebitPointsResponse
    {
        $lockPointResult = $this->lockPoints($points);

        if ($lockPointResult->isSuccessful()) {
            return $this->debitPoints($lockPointResult->getDocumentId());
        } else {
            return $lockPointResult->getMessage();
        }
    }

    public function getPoints($merchantId): GetPointsResponse
    {
        $response = $this->makeGetRequest("getPoints?merchant_id={$merchantId}");

        return new GetPointsResponse($response->getBody());
    }

    public function lockPoints($points): LockPointsResponse
    {
        $response = $this->makePostRequest('lockPoints', ['points' => $points]);

        return new LockPointsResponse($response->getBody());
    }

    public function debitPoints($documentId): DebitPointsResponse
    {
        $this->makePostRequest('debitPoints', ['document_id' => $documentId]);

        return new DebitPointsResponse();
    }

    public function cancelTransaction($documentId): CancelTransactionResponse
    {
        $response = $this->makePostRequest('cancelTransaction', ['document_id' => $documentId]);

        return new CancelTransactionResponse($response->getBody());
    }

    public function reversePoint($documentId): ReversePointResponse
    {
        $response = $this->makePostRequest('reversePoints', ['document_id' => $documentId]);

        return new ReversePointResponse($response->getBody());
    }

    protected function makeGetRequest($apiTransaction)
    {
        try {
            return $this->http_client->get(
                "{$this->api_url}/{$apiTransaction}",
                $this->buildHeaders()
            );
        } catch (PlaceToPayException $e) {
            return new PlaceToPayClient(null, $e->getErrorCode(), $e->getMessage());
        } catch (Exception $e) {
            return new PlaceToPayClient(null, null, $e->getMessage());
        }
    }

    protected function makePostRequest($apiTransaction, $body)
    {
        try {
            return $this->http_client->post(
                "{$this->api_url}/{$apiTransaction}",
                $this->buildHeaders(),
                $body
            );
        } catch (PlaceToPayException $e) {
            return new PlaceToPayClient(null, $e->getErrorCode(), $e->getMessage());
        } catch (Exception $e) {
            return new PlaceToPayClient(null, null, $e->getMessage());
        }
    }

    protected function buildHeaders(): array
    {
        if (! isset($this->authToken)) {
            throw new PlaceToPayException(401, 'Missing access token');
        }

        if ($this->tokenExpired()) {
            $this->refreshToken();
        }

        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$this->authToken}",
        ];
    }

    protected function tokenExpired()
    {
        return $this->cache->hasItem('access_token');
    }

    protected function refreshToken()
    {
        $this->getBearerToken($this->client_id, $this->client_secret);
    }

    protected function storeToken($result)
    {
        $this->authToken = $result['access_token'];
        $this->refreshToken = $result['refresh_token'];
        $this->expireAt = $result['expire_at'];

        $this->storeValueInCache('access_token', $this->authToken, $this->expireAt);
        $this->storeValueInCache('refresh_token', $this->refreshToken);
        $this->storeValueInCache('expire_at', $this->expireAt);
        $this->storeValueInCache('client_id', $this->client_id);
        $this->storeValueInCache('client_secret', $this->client_secret);
    }

    private function getBearerToken($client_id, $client_secret)
    {
        $url = "{$this->api_url}/oauth2/token?grant_type=authorization_code&client_id={$client_id}
        &client_secret={$client_secret}&scope=write&redirect_uri={$this->redirect_url}";
        $response = $this->http_client->get($url, []);
        $this->storeToken(json_decode($response->getBody(), true));
    }

    private function storeValueInCache($key, $value, $expireAt = null)
    {
        $item = $this->cache->getItem($key);
        $item->set($value);
        if ($expireAt != null) {
            $item->expiresAfter($this->expireAt);
        }
    }

    private function loadSessionData()
    {
        $this->authToken = $this->cache->getItem('access_token');
        $this->refreshToken = $this->cache->getItem('refresh_token');
        $this->expireAt = $this->cache->getItem('expire_at');
        $this->client_id = $this->cache->getItem('client_id');
        $this->client_secret = $this->cache->getItem('client_secret');
    }
}
