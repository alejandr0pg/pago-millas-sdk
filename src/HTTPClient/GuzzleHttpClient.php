<?php

declare(strict_types=1);

namespace PlacetoPay\HTTPClient;

use GuzzleHttp\Client;
use PlacetoPay\Exception\ExceptionFactory;
use PlacetoPay\HTTPClient\Interfaces\HTTPClient;
use PlacetoPay\HTTPClient\Model\HTTPResponse;

/**
 * Class GuzzleHttpClient.
 */
class GuzzleHttpClient implements HTTPClient
{
    /**
     * GuzzleHttpClient constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param $url
     * @param $headers
     * @return HTTPResponse|null
     * @throws \PlacetoPay\Exception\PlaceToPayException
     */
    public function get($url, $headers): ?HTTPResponse
    {
        $client = $this->buildClient();
        $response = $client->get($url, $headers);

        return $this->handleResponse($response);
    }

    /**
     * @param $url
     * @param $headers
     * @param $body
     * @return HTTPResponse|null
     * @throws \PlacetoPay\Exception\PlaceToPayException
     */
    public function post($url, $headers, $body): ?HTTPResponse
    {
        $client = $this->buildClient();
        $response = $client->post($url, ['body' =>  json_encode($body), 'headers' => $headers]);

        return $this->handleResponse($response);
    }

    /**
     * @param $url
     * @param $headers
     * @return HTTPResponse|null
     */
    public function put($url, $headers): ?HTTPResponse
    {
        return null;
    }

    /**
     * @param $response
     * @return HTTPResponse|null
     * @throws \PlacetoPay\Exception\PlaceToPayException
     */
    public function handleResponse($response): ?HTTPResponse
    {
        $body = $response->getBody();
        $statusCode = $response->getStatusCode();

        //TODO revisar bien que codigos lanzaran errores
        if ($statusCode == 400 || $statusCode == 401) {
            throw ExceptionFactory::buildException($body);
        } else {
            return new HTTPResponse(
                $response->getHeaders(),
                $statusCode,
                $body
            );
        }
    }

    /**
     * @return Client
     */
    public function buildClient()
    {
        return new Client(['http_errors' => false]);
    }
}
