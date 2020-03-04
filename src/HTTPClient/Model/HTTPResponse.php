<?php

namespace PlacetoPay\HTTPClient\Model;

class HTTPResponse
{
    private  $status_code;
    private  $headers;
    private  $body;

    function __construct($headers,  $statusCode,  $body)
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
