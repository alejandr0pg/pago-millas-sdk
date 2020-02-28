<?php

namespace PlaceToPay\SDK\HTTPClient\Interfaces;

use PlaceToPay\SDK\HTTPClient\Model\HTTPResponse;

interface HTTPClient
{
    public function get(string $url, ?array $headers): ?HTTPResponse;

    public function post(string $url, ?array $headers, ?array $body): ?HTTPResponse;

    public function put(string $url, ?array $headers): ?HTTPResponse;
}
