<?php

declare(strict_types=1);

namespace PlaceToPay\SDK\HTTPClient;

use GuzzleHttp\Client;
use PlaceToPay\SDK\Exception\ExceptionFactory;
use PlaceToPay\SDK\HTTPClient\Interfaces\HTTPClient;
use PlaceToPay\SDK\HTTPClient\Model\HTTPResponse;


class GuzzleHttpClient implements HTTPClient
{

    function __construct()
    {
    }

    public function get(string $url, ?array $headers): ?HTTPResponse
    {
        $client = $this->buildClient();
        $response = $client->get($url, $headers);
        return $this->handleResponse($response);
    }


    public function post(string $url, ?array $headers, ?array $body): ?HTTPResponse
    {

        $client = $this->buildClient();
        $response = $client->post($url, ['body' =>  json_encode($body), 'headers' => $headers]);
        return $this->handleResponse($response);
    }

    public function handleResponse($response): ?HTTPResponse
    {
        $body = (string) $response->getBody();
        $statusCode = $response->getStatusCode();
        //TODO revisar bien que codigos lanzaran errores
        if ($statusCode >= 400) {
            throw ExceptionFactory::buildException($statusCode, $body);
        } else {
            return new HTTPResponse(
                $response->getHeaders(),
                $statusCode,
                $body
            );
        }
    }

    public function  put(string $url, ?array $headers): ?HTTPResponse
    {
        return null;
    }

    public function buildClient()
    {
        return new Client(['http_errors' => false]);
    }
}
