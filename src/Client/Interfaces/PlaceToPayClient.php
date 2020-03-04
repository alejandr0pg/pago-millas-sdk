<?php

declare(strict_types=1);

namespace PlacetoPay\Client\Interfaces;

use PlacetoPay\Models\CancelTransactionResponse;
use PlacetoPay\Models\DebitPointsResponse;
use PlacetoPay\Models\GetPointsResponse;
use PlacetoPay\Models\LockPointsResponse;
use PlacetoPay\Models\PlaceToPayResponse;
use PlacetoPay\Models\ReversePointResponse;

interface PlaceToPayClient
{
    public function getPoints($merchantId): GetPointsResponse;

    public function lockPoints($points): LockPointsResponse;

    public function lockAndDebitPoints($point): DebitPointsResponse;

    public function debitPoints($documentId): DebitPointsResponse;

    public function cancelTransaction($documentId): CancelTransactionResponse;

    public function reversePoint($documentId): ReversePointResponse;
}
