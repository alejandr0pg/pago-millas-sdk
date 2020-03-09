<?php

declare(strict_types=1);

use Mockery as mocker;
use PHPUnit\Framework\TestCase;
use PlacetoPay\Client\DefaultPlaceToPayClient;
use PlacetoPay\HTTPClient\Model\HTTPResponse;
use PlacetoPay\Models\DebitPointsResponse;
use PlacetoPay\Models\LockPointsResponse;

/**
 * Class DefaultPlaceToPayClientTest.
 */
final class DefaultPlaceToPayClientTest extends TestCase
{
    /** @test */
    public function shouldCreateADefaultPlaceToPayClient(): void
    {
        $defaultPlaceToPayClient = $this->buildClient();
        $this->assertInstanceOf(
            DefaultPlaceToPayClient::class,
            $defaultPlaceToPayClient
        );
    }

    /** @test */
    public function shouldLockAndDebitPoints(): void
    {
        $defaultPlaceToPayClient = $this->buildClient();
        $httpClient = mocker::mock('PlacetoPay\HTTPClient\GuzzleHttpClient');

        $this->mockProperty($defaultPlaceToPayClient, 'api_url', 'localhost:8080');
        $this->mockProperty($defaultPlaceToPayClient, 'authToken', 'authToken');
        $this->mockProperty($defaultPlaceToPayClient, 'http_client', $httpClient);
        $this->mockProperty($defaultPlaceToPayClient, 'expireAt', '7200');
        $this->mockProperty($defaultPlaceToPayClient, 'refreshToken', 'authToken');

        $defaultPlaceToPayClient->shouldReceive('tokenExpired')
            ->andReturn(false);

        $defaultPlaceToPayClient->shouldReceive('lockPoints')
            ->andReturn(new LockPointsResponse('{"Data": {"miles":1, "document_id": 2},"Message": "test message"}'));

        $httpClient->shouldReceive('post')
            ->andReturn(new HTTPResponse(
                ['Content-Type' => 'application/json'],
                200,
                json_encode([
                    'Data' => ['miles' => 0, 'index_conversion' => 0],
                    'Message' => 'test message',
                ])
            ));

        $response = $defaultPlaceToPayClient->lockAndDebitPoints(1);

        $this->assertEqualsIgnoringCase(null, null);
        $this->assertInstanceOf(
            DebitPointsResponse::class,
            $response
        );
    }

    /**
     * @return mocker\Mock
     * @throws ReflectionException
     */
    private function buildClient()
    {
        $mock = mocker::mock('PlacetoPay\Client\DefaultPlaceToPayClient')
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();
        $this->mockProperty($mock, 'client_id', 'client_id');
        $this->mockProperty($mock, 'client_secret', 'client_secret');
        $this->mockProperty($mock, 'redirect_url', 'redirect_url');
        $this->mockProperty($mock, 'api_url', 'apiUrl');

        return $mock;
    }

    /**
     * @param $object
     * @param $propertyName
     * @param $value
     * @throws ReflectionException
     */
    public function mockProperty($object, $propertyName, $value)
    {
        $reflectionClass = new \ReflectionClass($object);
        $property = $reflectionClass->getProperty($propertyName);
        $property->setAccessible(true);
        $property->setValue($object, $value);
        $property->setAccessible(false);
    }
}
