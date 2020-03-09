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
use PlacetoPay\Models\ReversePointsResponse;

/**
 * Class AbstractPlaceToPayClient.
 */
abstract class AbstractPlaceToPayClient implements PlaceToPayClient
{
    protected $api_url;
    protected $redirect_url;
    protected $client_id;
    protected $client_secret;
    protected $http_client;
    protected $authToken;
    protected $refreshToken;
    protected $expireAt;
    protected $cache;

    /**
     * AbstractPlaceToPayClient constructor.
     *
     * @param $http_client
     * @param $api_url
     * @param $redirect_url
     * @param $client_id
     * @param $client_secret
     * @param $cache
     */
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

    /**
     * @param $client_id
     * @param $client_secret
     */
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

    /**
     * @param $points
     * @return DebitPointsResponse
     */
    public function lockAndDebitPoints($points): DebitPointsResponse
    {
        $lockPointResult = $this->lockPoints($points);

        if ($lockPointResult->isSuccessful()) {
            return $this->debitPoints($lockPointResult->getDocumentId());
        } else {
            return new DebitPointsResponse($lockPointResult->getMessage(), $lockPointResult->getErrorMessage(), $lockPointResult->getErrorCode());
        }
    }

    /**
     * @param $merchantId
     * @return GetPointsResponse
     */
    public function getPoints($merchantId): GetPointsResponse
    {
        $response = $this->makeGetRequest("getPoints?merchant_id={$merchantId}");

        return new GetPointsResponse($response['body'], $response['errorMessage'], $response['errorCode']);
    }

    /**
     * @param $points
     * @return LockPointsResponse
     */
    public function lockPoints($points): LockPointsResponse
    {
        $response = $this->makePostRequest('lockPoints', ['points' => $points]);

        return new LockPointsResponse($response['body'], $response['errorMessage'], $response['errorCode']);
    }

    /**
     * @param $documentId
     * @return DebitPointsResponse
     */
    public function debitPoints($documentId): DebitPointsResponse
    {
        $response = $this->makePostRequest('debitPoints', ['document_id' => $documentId]);

        return new DebitPointsResponse($response['body'], $response['errorMessage'], $response['errorCode']);
    }

    /**
     * @param $documentId
     * @return CancelTransactionResponse
     */
    public function cancelTransaction($documentId): CancelTransactionResponse
    {
        $response = $this->makePostRequest('cancelTransaction', ['document_id' => $documentId]);

        return new CancelTransactionResponse($response['body'], $response['errorMessage'], $response['errorCode']);
    }

    /**
     * @param $documentId
     * @return ReversePointsResponse
     */
    public function reversePoint($documentId): ReversePointsResponse
    {
        $response = $this->makePostRequest('reversePoints', ['document_id' => $documentId]);

        return new ReversePointsResponse($response['body'], $response['errorMessage'], $response['errorCode']);
    }

    /**
     * @param $apiTransaction
     * @return array
     */
    protected function makeGetRequest($apiTransaction)
    {
        try {
            $httpResponse = $this->http_client->get(
                "{$this->api_url}/{$apiTransaction}",
                $this->buildHeaders()
            );

            return [
                'body' => $httpResponse->getBody(),
                'errorMessage' => null,
                'errorCode' => null,
            ];
        } catch (PlaceToPayException $e) {
            return [
                'body' => null,
                'errorMessage' => $e->getMessage(),
                'errorCode' => $e->getErrorCode(),
            ];
        } catch (Exception $e) {
            return [
                'body' => null,
                'errorMessage' => $e->getMessage(),
                'errorCode' => null,
            ];
        }
    }

    /**
     * @param $apiTransaction
     * @param $body
     * @return array
     */
    protected function makePostRequest($apiTransaction, $body)
    {
        try {
            $httpResponse = $this->http_client->post(
                "{$this->api_url}/{$apiTransaction}",
                $this->buildHeaders(),
                $body
            );

            return [
                'body' => $httpResponse->getBody(),
                'errorMessage' => null,
                'errorCode' => null,
            ];
        } catch (PlaceToPayException $e) {
            return [
                'body' => null,
                'errorMessage' => $e->getMessage(),
                'errorCode' => $e->getErrorCode(),
            ];
        } catch (Exception $e) {
            return [
                'body' => null,
                'errorMessage' => $e->getMessage(),
                'errorCode' => null,
            ];
        }
    }

    /**
     * @return array
     * @throws PlaceToPayException
     */
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

    /**
     * @return mixed
     */
    protected function tokenExpired()
    {
        return $this->cache->hasItem('access_token');
    }

    /**
     * Function used to refresh token.
     */
    protected function refreshToken()
    {
        $this->getBearerToken($this->client_id, $this->client_secret);
    }

    /**
     * @param $result
     */
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

    /**
     * @param $client_id
     * @param $client_secret
     */
    private function getBearerToken($client_id, $client_secret)
    {
        $url = "{$this->api_url}/oauth2/token?grant_type=authorization_code&client_id={$client_id}
            &client_secret={$client_secret}&scope=write&redirect_uri={$this->redirect_url}";

        $response = $this->http_client->get($url, []);

        $this->storeToken(json_decode($response->getBody(), true));
    }

    /**
     * @param $key
     * @param $value
     * @param null $expireAt
     */
    private function storeValueInCache($key, $value, $expireAt = null)
    {
        $item = $this->cache->getItem($key);
        $item->set($value);

        if ($expireAt != null) {
            $item->expiresAfter($this->expireAt);
        }
    }

    /**
     * Function used to load data stored in session.
     */
    private function loadSessionData()
    {
        $this->authToken = $this->cache->getItem('access_token');
        $this->refreshToken = $this->cache->getItem('refresh_token');
        $this->expireAt = $this->cache->getItem('expire_at');
        $this->client_id = $this->cache->getItem('client_id');
        $this->client_secret = $this->cache->getItem('client_secret');
    }
}
