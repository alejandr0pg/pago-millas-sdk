<?php

declare(strict_types=1);

namespace PlaceToPay\SDK\Client\Interfaces;

use PlaceToPay\SDK\Models\PlaceToPayResponse;

interface PlaceToPayClient
{
    public function getPoints(string $merchantId): PlaceToPayResponse;

    public function lockPoints(int $points): PlaceToPayResponse;

    public function lockAndDebitPoints(int $point): PlaceToPayResponse;

    public function debitPoints(int $documentId): PlaceToPayResponse;

    public function cancelTransaction(int $documentId): PlaceToPayResponse;

    public function reversePoint(int $documentId): PlaceToPayResponse;
}
