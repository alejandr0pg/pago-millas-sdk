<?php

namespace PlacetoPay\HTTPClient\Interfaces;

use PlacetoPay\HTTPClient\Model\HTTPResponse;

interface HTTPClient
{
    public function get($url, $headers): ?HTTPResponse;

    public function post($url, $headers, $body): ?HTTPResponse;

    public function put($url, $headers): ?HTTPResponse;
}
