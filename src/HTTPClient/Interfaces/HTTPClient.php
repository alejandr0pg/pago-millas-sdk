<?php

namespace PlacetoPay\HTTPClient\Interfaces;

use PlacetoPay\HTTPClient\Model\HTTPResponse;

/**
 * Interface HTTPClient.
 */
interface HTTPClient
{
    /**
     * @param $url
     * @param $headers
     * @return HTTPResponse|null
     */
    public function get($url, $headers): ?HTTPResponse;

    /**
     * @param $url
     * @param $headers
     * @param $body
     * @return HTTPResponse|null
     */
    public function post($url, $headers, $body): ?HTTPResponse;

    /**
     * @param $url
     * @param $headers
     * @return HTTPResponse|null
     */
    public function put($url, $headers): ?HTTPResponse;
}
