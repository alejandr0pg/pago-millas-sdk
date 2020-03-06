<?php

declare(strict_types=1);

namespace PlacetoPay\Client\Interfaces;

use PlacetoPay\Models\CancelTransactionResponse;
use PlacetoPay\Models\DebitPointsResponse;
use PlacetoPay\Models\GetPointsResponse;
use PlacetoPay\Models\LockPointsResponse;
use PlacetoPay\Models\ReversePointsResponse;

/**
 * Interface PlaceToPayClient.
 */
interface PlaceToPayClient
{
    /**
     * @param $merchantId
     * @return GetPointsResponse
     */
    public function getPoints($merchantId): GetPointsResponse;

    /**
     * @param $points
     * @return LockPointsResponse
     */
    public function lockPoints($points): LockPointsResponse;

    /**
     * @param $point
     * @return DebitPointsResponse
     */
    public function lockAndDebitPoints($point): DebitPointsResponse;

    /**
     * @param $documentId
     * @return DebitPointsResponse
     */
    public function debitPoints($documentId): DebitPointsResponse;

    /**
     * @param $documentId
     * @return CancelTransactionResponse
     */
    public function cancelTransaction($documentId): CancelTransactionResponse;

    /**
     * @param $documentId
     * @return ReversePointsResponse
     */
    public function reversePoint($documentId): ReversePointsResponse;
}
