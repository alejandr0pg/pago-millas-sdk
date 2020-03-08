<?php

namespace PlacetoPay\HTTPClient\Model;

/**
 * Class HTTPResponse.
 */
class HTTPResponse
{
    private $status_code;
    private $headers;
    private $body;


    /**
     * HTTPResponse constructor.
     *
     * @param $headers
     * @param $statusCode
     * @param $body
     */
    public function __construct($headers, $statusCode,  $body)
    {
        $this->headers = $headers;
        $this->status_code = $statusCode;
        $this->body = $body;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return mixed
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->status_code;
    }
}
