<?php

declare(strict_types=1);

use Mockery as mocker;
use PHPUnit\Framework\TestCase;
use PlacetoPay\Exception\ExpiredTokenException;
use PlacetoPay\Exception\NotValidTokenException;
use PlacetoPay\Exception\UnauthorizedException;
use PlacetoPay\HTTPClient\Model\HTTPResponse;
use PlacetoPay\Models\ReversePointsResponse;

final class RevertPointsTest extends TestCase
{



    /** @test */
    public function shouldReturnAPlaceToPayResponseWithDataContainingDocumentIdAndMilesWhenRevertingPoints(): void
    {
        $defaultPlaceToPayClient = $this->buildClient();

        $httpClient = mocker::mock('PlacetoPay\HTTPClient\GuzzleHttpClient');

        $defaultPlaceToPayClient->shouldReceive('tokenExpired')
            ->andReturn(false);

        $httpClient->shouldReceive('post')
            ->andReturn(new HTTPResponse(
                ['Content-Type' => 'application/json'],
                200,
                json_encode([])
            ));

        $this->mockProperty($defaultPlaceToPayClient, 'api_url', 'localhost:8080');
        $this->mockProperty($defaultPlaceToPayClient, 'authToken', 'authToken');
        $this->mockProperty($defaultPlaceToPayClient, 'http_client', $httpClient);
        $this->mockProperty($defaultPlaceToPayClient, 'expireAt', '7200');
        $this->mockProperty($defaultPlaceToPayClient, 'refreshToken', 'authToken');

        $response = $defaultPlaceToPayClient->reversePoint('document_id');

        $this->assertEqualsIgnoringCase($response->isSuccessful(), true);
        $this->assertInstanceOf(
            ReversePointsResponse::class,
            $response
        );
    }


    /** @test */
    public function shouldReturnAPlaceToPayResponseWithIsSuccessFalseAndNotValidTokenErrorWhenRevertingPoints(): void
    {
        $defaultPlaceToPayClient = $this->buildClient();
        $httpClient = mocker::mock('PlacetoPay\HTTPClient\GuzzleHttpClient');

        $defaultPlaceToPayClient->shouldReceive('tokenExpired')
            ->andReturn(false);

        $httpClient->shouldReceive('post')
            ->andThrow(
                new NotValidTokenException('NOT_VALID_TOKEN', 'El bearer token no es válido')
            );

        $this->mockProperty($defaultPlaceToPayClient, 'api_url', 'localhost:8080');
        $this->mockProperty($defaultPlaceToPayClient, 'authToken', 'authToken');
        $this->mockProperty($defaultPlaceToPayClient, 'http_client', $httpClient);
        $this->mockProperty($defaultPlaceToPayClient, 'expireAt', '7200');
        $this->mockProperty($defaultPlaceToPayClient, 'refreshToken', 'authToken');

        $response =  $defaultPlaceToPayClient->reversePoint('document_id');

        $this->assertEqualsIgnoringCase($response->getErrorMessage(), 'El bearer token no es válido');
        $this->assertEqualsIgnoringCase($response->getErrorCode(), 'NOT_VALID_TOKEN');
        $this->assertEqualsIgnoringCase($response->isSuccessful(), false);
        $this->assertInstanceOf(
            ReversePointsResponse::class,
            $response
        );
    }


    /** @test */
    public function shouldReturnAPlaceToPayResponseWithIsSuccessFalseAndExpiredTokenErrorWhenRevertingPoints(): void
    {
        $defaultPlaceToPayClient = $this->buildClient();
        $httpClient = mocker::mock('PlacetoPay\HTTPClient\GuzzleHttpClient');

        $defaultPlaceToPayClient->shouldReceive('tokenExpired')
            ->andReturn(false);

        $httpClient->shouldReceive('post')
            ->andThrow(
                new ExpiredTokenException('EXPIRED_TOKEN', 'El bearer token ha expirado y se necesita actualizarlo')
            );

        $this->mockProperty($defaultPlaceToPayClient, 'api_url', 'localhost:8080');
        $this->mockProperty($defaultPlaceToPayClient, 'authToken', 'authToken');
        $this->mockProperty($defaultPlaceToPayClient, 'http_client', $httpClient);
        $this->mockProperty($defaultPlaceToPayClient, 'expireAt', '7200');
        $this->mockProperty($defaultPlaceToPayClient, 'refreshToken', 'authToken');

        $response =  $defaultPlaceToPayClient->reversePoint('document_id');

        $this->assertEqualsIgnoringCase($response->getErrorMessage(), 'El bearer token ha expirado y se necesita actualizarlo');
        $this->assertEqualsIgnoringCase($response->getErrorCode(), 'EXPIRED_TOKEN');
        $this->assertEqualsIgnoringCase($response->isSuccessful(), false);
        $this->assertInstanceOf(
            ReversePointsResponse::class,
            $response
        );
    }


    /** @test */
    public function shouldReturnAPlaceToPayResponseWithIsSuccessFalseAndUnauthorizedErrorWhenRevertingPoints(): void
    {
        $defaultPlaceToPayClient = $this->buildClient();
        $httpClient = mocker::mock('PlacetoPay\HTTPClient\GuzzleHttpClient');

        $defaultPlaceToPayClient->shouldReceive('tokenExpired')
            ->andReturn(false);

        $httpClient->shouldReceive('post')
            ->andThrow(
                new UnauthorizedException('UNAUTHORIZED', 'No autorizado')
            );

        $this->mockProperty($defaultPlaceToPayClient, 'api_url', 'localhost:8080');
        $this->mockProperty($defaultPlaceToPayClient, 'authToken', 'authToken');
        $this->mockProperty($defaultPlaceToPayClient, 'http_client', $httpClient);
        $this->mockProperty($defaultPlaceToPayClient, 'expireAt', '7200');
        $this->mockProperty($defaultPlaceToPayClient, 'refreshToken', 'authToken');

        $response =  $defaultPlaceToPayClient->reversePoint('document_id');

        $this->assertEqualsIgnoringCase($response->getErrorMessage(), 'No autorizado');
        $this->assertEqualsIgnoringCase($response->getErrorCode(), 'UNAUTHORIZED');
        $this->assertEqualsIgnoringCase($response->isSuccessful(), false);
        $this->assertInstanceOf(
            ReversePointsResponse::class,
            $response
        );
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
}
