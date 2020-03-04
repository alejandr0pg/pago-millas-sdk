<?php

declare(strict_types=1);

namespace PlacetoPay\Client\Interfaces;

interface PlaceToPayClientBuilder
{
    public function withApiUrl($baseURL): PlaceToPayClientBuilder;

    public function withUsername($username): PlaceToPayClientBuilder;

    public function withPassword($password): PlaceToPayClientBuilder;

    public function withLogger($logger): PlaceToPayClientBuilder;

    public function build(): PlaceToPayClient;
}
