<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PlacetoPay\Client\DefaultPlaceToPayClient;
use PlacetoPay\Models\PlaceToPayResponse;
use Mockery as mocker;
use PlacetoPay\Exception\PlaceToPayException;
use PlacetoPay\HTTPClient\Model\HTTPResponse;
use PlacetoPay\Models\DebitPointsResponse;
use PlacetoPay\Models\LockPointsResponse;

use function PHPUnit\Framework\assertThat;
use function PHPUnit\Framework\equalTo;

final class DefaultPlaceToPayClientTest extends TestCase
{


    public function testShouldCreateADefaultPlaceToPayClient(): void
    {
        $defaultPlaceToPayClient = $this->buildClient();
        $this->assertInstanceOf(
            DefaultPlaceToPayClient::class,
            $defaultPlaceToPayClient
        );
    }


    public function testShouldReturnAPlaceToPayResponseWithDataWhenAskingForPoints(): void
    {
        $defaultPlaceToPayClient = $this->buildClient();
        $httpClient = mocker::mock('PlacetoPay\HTTPClient\GuzzleHttpClient');

        $defaultPlaceToPayClient->shouldReceive('tokenExpired')
            ->andReturn(false);

        $httpClient->shouldReceive('get')
            ->andReturn(new HTTPResponse(
                ['Content-Type' => 'application/json'],
                200,
                json_encode([
                    "Data" => ["miles" => 0, "index_conversion" => 0],
                    "Message" => "test message"
                ])
            ));

        $this->mockProperty($defaultPlaceToPayClient, 'api_url', 'localhost:8080');
        $this->mockProperty($defaultPlaceToPayClient, 'authToken', 'authToken');
        $this->mockProperty($defaultPlaceToPayClient, 'http_client',  $httpClient);
        $this->mockProperty($defaultPlaceToPayClient, 'expireAt',  '7200');
        $this->mockProperty($defaultPlaceToPayClient, 'refreshToken',  'authToken');


        $response = $defaultPlaceToPayClient->getPoints("test");

        $this->assertEqualsIgnoringCase($response->getMiles(), 0);
        $this->assertEqualsIgnoringCase($response->getIndexOfConversion(), 0);
        $this->assertEqualsIgnoringCase($response->getMessage(), 'test message');
    }


    public function testShouldReturnAPlaceToPayResponseWithDataContainingDocumentIdAndMilesWhenLockingPoints(): void
    {
        $defaultPlaceToPayClient = $this->buildClient();

        $httpClient = mocker::mock('PlacetoPay\HTTPClient\GuzzleHttpClient');

        $defaultPlaceToPayClient->shouldReceive('tokenExpired')
            ->andReturn(false);

        $httpClient->shouldReceive('post')
            ->andReturn(new HTTPResponse(
                ['Content-Type' => 'application/json'],
                200,
                json_encode([
                    "Data" => ["miles" => 1, "document_id" => 2],
                    "Message" => "test message"
                ])
            ));

        $this->mockProperty($defaultPlaceToPayClient, 'api_url', 'localhost:8080');
        $this->mockProperty($defaultPlaceToPayClient, 'authToken', 'authToken');
        $this->mockProperty($defaultPlaceToPayClient, 'http_client',  $httpClient);
        $this->mockProperty($defaultPlaceToPayClient, 'expireAt',  '7200');
        $this->mockProperty($defaultPlaceToPayClient, 'refreshToken',  'authToken');


        $response = $defaultPlaceToPayClient->lockPoints(10);

        $this->assertEqualsIgnoringCase($response->getMiles(), 1);
        $this->assertEqualsIgnoringCase($response->getDocumentId(), 2);
        $this->assertEqualsIgnoringCase($response->getMessage(), "test message");
        $this->assertInstanceOf(
            LockPointsResponse::class,
            $response
        );
    }



    public function testShouldLockAndDebitPoints(): void
    {

        $defaultPlaceToPayClient = $this->buildClient();
        $httpClient = mocker::mock('PlacetoPay\HTTPClient\GuzzleHttpClient');


        $this->mockProperty($defaultPlaceToPayClient, 'api_url', 'localhost:8080');
        $this->mockProperty($defaultPlaceToPayClient, 'authToken', 'authToken');
        $this->mockProperty($defaultPlaceToPayClient, 'http_client',  $httpClient);
        $this->mockProperty($defaultPlaceToPayClient, 'expireAt',  '7200');
        $this->mockProperty($defaultPlaceToPayClient, 'refreshToken',  'authToken');

        $defaultPlaceToPayClient->shouldReceive('tokenExpired')
            ->andReturn(false);

        $defaultPlaceToPayClient->shouldReceive('lockPoints')
            ->andReturn(new LockPointsResponse('{"Data": {"miles":1, "document_id": 2},"Message": "test message"}'));

        $httpClient->shouldReceive('post')
            ->andReturn(new HTTPResponse(
                ['Content-Type' => 'application/json'],
                200,
                json_encode([
                    "Data" => ["miles" => 0, "index_conversion" => 0],
                    "Message" => "test message"
                ])
            ));

        $response = $defaultPlaceToPayClient->lockAndDebitPoints(1);

        $this->assertEqualsIgnoringCase(null, null);
        $this->assertInstanceOf(
            DebitPointsResponse::class,
            $response
        );
    }

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

    public  function mockProperty($object, $propertyName, $value)
    {
        $reflectionClass = new \ReflectionClass($object);
        $property = $reflectionClass->getProperty($propertyName);
        $property->setAccessible(true);
        $property->setValue($object, $value);
        $property->setAccessible(false);
    }
}
