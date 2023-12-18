<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Service\TokenService;
use App\Tests\ApiTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class CreateProductTest extends ApiTestCase
{
    private const CREATE_PRODUCT = '/api/records/create';


    /**
     * @test
     * @dataProvider getCreateProductDataProvider
     * @throws TransportExceptionInterface
     */
    public function testCreateProduct(
        $requestBody,
        $contentType,
        $expectedResponse,
        $statusCode
    ): void
    {
        $token = $this->tokenservice->generateToken('admin', 12345);
        $response = static::createApiClient()->request(
            'POST',
            self::CREATE_PRODUCT,
            [
                'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json', 'Authorization' => 'Bearer ' . $token],
                'body' => json_encode($requestBody),
            ]
        );

        $this->assertEquals($statusCode, $response->getStatusCode());

        if ($expectedResponse) {
            $result = $response->toArray();
            $this->assertIsArray($result);
        }
    }

    public static function getCreateProductDataProvider()
    {
        return [
            'Success scenario' => [
                self::successPayload()['requestPayload'],
                'contentType' => 'application/json',
                self::successPayload()['expectedOutput'],
                Response::HTTP_CREATED,
            ],
            'Failure scenario with invalid payload' => [
                [],
                'contentType' => 'application/json',
                [],
                Response::HTTP_BAD_REQUEST,
            ],
            'Failure scenario with duplicate SKU' => [
                self::failurePayloadWithNullSKU()['requestPayload'],
                'contentType' => 'application/json',
                [],
                Response::HTTP_BAD_REQUEST,
            ],
            'Failure scenario with missing SKU' => [
                self::partialPayLoad()['requestPayload'],
                'contentType' => 'application/json',
                [],
                Response::HTTP_MULTI_STATUS,
            ],
        ];
    }

    public static function successPayload(): array
    {
        return [
            'requestPayload' => [[
                'sku' => 'SKU15432523',
                'product_name' => 'Test Product',
                'description' => 'This is a test product.',
            ], ['sku' => 'SKU15432524',
                'product_name' => 'Test Product',
                'description' => 'This is a test product.',
            ]],
            'expectedOutput' => [
                'message' => '2 record(s) loaded successfully',
            ],
        ];
    }
    public static function failurePayloadWithNullSKU(): array
    {
        return [
            'requestPayload' => [[
                'sku' => '',
                'product_name' => 'Duplicate Product',
                'description' => 'This product has a duplicate SKU.',
            ], [
                'sku' => '',
                'product_name' => 'Duplicate Product',
                'description' => 'This product has a duplicate SKU.',
            ]],
        ];
    }
    public static function partialPayLoad(): array
    {
        return [
            'requestPayload' => [[
                'sku' => '3432',
                'product_name' => 'Duplicate Product',
                'description' => 'This product has a duplicate SKU.',
            ], [
                'sku' => '',
                'product_name' => 'Duplicate Product',
                'description' => 'This product has a duplicate SKU.',
            ]],
        ];
    }
}
