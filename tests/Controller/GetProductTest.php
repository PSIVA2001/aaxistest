<?php

namespace App\Tests\Controller;

use App\Tests\ApiTestCase;

class GetProductTest extends ApiTestCase
{
    private const LIST_ORDER = '/api/records/list';
    public function testGetOrder(): void {
        $token = $this->tokenservice->generateToken('admin', 12345);
        $response = static::createApiClient()->request(
            'GET',
            self::LIST_ORDER,   [
                'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json', 'Authorization' => 'Bearer ' . $token],
            ]
        );
        $this->assertEquals('200', $response->getStatusCode());
        $result = $response->toArray();
        $this->assertIsArray($result);
    }

}
