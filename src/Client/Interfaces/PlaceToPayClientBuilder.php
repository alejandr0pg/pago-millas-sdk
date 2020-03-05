<?php

declare(strict_types=1);

namespace PlacetoPay\Client\Interfaces;

interface PlaceToPayClientBuilder
{
    public function withApiUrl($baseURL): self;

    public function withUsername($username): self;

    public function withPassword($password): self;

    public function withLogger($logger): self;

    public function build(): PlaceToPayClient;
}
