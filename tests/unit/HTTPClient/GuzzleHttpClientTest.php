<?php

declare(strict_types=1);

use Mockery as mocker;
use PHPUnit\Framework\TestCase;
use PlacetoPay\Exception\PlaceToPayException;
use PlacetoPay\HTTPClient\GuzzleHttpClient;
use PlacetoPay\HTTPClient\Model\HTTPResponse;

final class GuzzleHttpClientTest extends TestCase
{

    public function testCanBeCreatedFromDefaultConstructor(): void
    {
        $this->assertInstanceOf(
            GuzzleHttpClient::class,
            new GuzzleHttpClient()
        );
    }

    public function testShouldMakeAGetRequestToTheGivenUrlAndReturnAHttpResponseObject(): void
    {
        $client = new GuzzleHttpClient();

        $response = $client->get("http://google.com", null);

        $this->assertInstanceOf(
            HTTPResponse::class,
            $response
        );
    }

    public function testShouldMakeAGetRequestToTheGivenUrlAndReturnAHttpResponseObjectWithStatus200(): void
    {
        $client = new GuzzleHttpClient();
        $response = $client->get("http://google.com",  []);
        $statusCode = $response->getStatusCode();
        $this->assertTrue(
            $statusCode === 200
        );
    }



    public function testShouldThrowAnInvalidGrantException(): void
    {
        $this->expectException(PlaceToPayException::class);
        $client = new GuzzleHttpClient();
        $client->handleResponse(new HTTPResponse(
            ['Content-Type' => 'application/json'],
            401,
            json_encode([
                "error" => "invalid_grant",
                "error_description" => "he provided authorization grant (e.g., authorization code, resource owner credentials) or refresh token is invalid, expired, revoked, 
                does not match the redirection URI used in the authorization request, or was issued to another client"
            ])
        ));
    }

    public function testShouldMakeAPostRequestToReturningWithStatusCode201(): void
    {
        $client = new GuzzleHttpClient();
        $response =  $client->handleResponse(new HTTPResponse(
            ['Content-Type' => 'application/json'],
            201,
            json_encode([])
        ));
        $statusCode = $response->getStatusCode();
        $this->assertTrue(
            $statusCode === 201
        );
    }
}
