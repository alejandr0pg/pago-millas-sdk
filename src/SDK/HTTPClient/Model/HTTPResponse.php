<?php

namespace PlaceToPay\SDK\HTTPClient\Model;

class HTTPResponse
{
    private int $status_code;
    private ?array   $headers;
    private ?string  $body;

    function __construct(?array $headers, ?int $statusCode, ?string $body)
    {
        $this->headers = $headers;
        $this->status_code = $statusCode;
        $this->body = $body;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getStatusCode()
    {
        return $this->status_code;
    }
}
