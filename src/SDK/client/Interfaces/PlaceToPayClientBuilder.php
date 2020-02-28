<?php

declare(strict_types=1);

namespace PlaceToPay\SDK\Client\Interfaces;

interface PlaceToPayClientBuilder
{
    public function withApiUrl(string $baseURL): PlaceToPayClientBuilder;

    public function withUsername(string $username): PlaceToPayClientBuilder;

    public function withPassword(string $password): PlaceToPayClientBuilder;

    public function withLogger($logger): PlaceToPayClientBuilder;

    public function build(): PlaceToPayClient;
}
