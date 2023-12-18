<?php

namespace App\Tests\Service;

use App\Tests\ApiTestCase;

class TokenServiceTest extends ApiTestCase
{
    public function testTokenCreation(){
        $successCredentials = $this->tokenservice->generateToken('admin',12345);
        $this->assertIsString($successCredentials);
    }
}