<?php

declare(strict_types=1);

namespace PlacetoPay\HTTPClient;

use GuzzleHttp\Client;
use PlacetoPay\Exception\ExceptionFactory;
use PlacetoPay\HTTPClient\Interfaces\HTTPClient;
use PlacetoPay\HTTPClient\Model\HTTPResponse;

class GuzzleHttpClient implements HTTPClient
{
    public function __construct()
    {
    }

    public function get($url, $headers): ?HTTPResponse
    {
        $client = $this->buildClient();
        $response = $client->get($url, $headers);
        return $this->handleResponse($response);
    }


    public function post($url, $headers, $body): ?HTTPResponse
    {
        $client = $this->buildClient();
        $response = $client->post($url, ['body' =>  json_encode($body), 'headers' => $headers]);
        return $this->handleResponse($response);
    }

    public function put($url, $headers): ?HTTPResponse
    {
        return null;
    }

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


    public function buildClient()
    {
        return new Client(['http_errors' => false]);
    }
}
