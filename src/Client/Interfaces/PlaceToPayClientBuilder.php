<?php

declare(strict_types=1);

namespace PlacetoPay\Client\Interfaces;

/**
 * Interface PlaceToPayClientBuilder.
 */
interface PlaceToPayClientBuilder
{
    /**
     * @param $baseURL
     * @return $this
     */
    public function withApiUrl($baseURL): self;

    /**
     * @param $username
     * @return $this
     */
    public function withUsername($username): self;

    /**
     * @param $password
     * @return $this
     */
    public function withPassword($password): self;

    /**
     * @param $logger
     * @return $this
     */
    public function withLogger($logger): self;

    /**
     * @return PlaceToPayClient
     */
    public function build(): PlaceToPayClient;
}
