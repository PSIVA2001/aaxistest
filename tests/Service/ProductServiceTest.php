<?php

namespace App\Tests\Service;


use App\Service\ProductService;
use App\Tests\ApiTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductServiceTest extends ApiTestCase
{



    public function setUp(): void
    {
        $this->productService = $this->createMock(ProductService::class);
    }

    public function testCreateOrder(): void
    {
        $data = $this->getData();
        $serviceResult = $this->productService->createRecords($data);
        $this->assertIsArray($serviceResult);
    }
    public function testUpdateOrder(): void
    {
        $data = $this->getData();
        $serviceResult = $this->productService->updateProducts($data);
        $this->assertIsArray($serviceResult);
    }


    public function getData(){
        $data = [[
            'sku' => 'abc',
            'product_name' => 'Test Product',
            'description' => 'This is a test product.',
        ],[
            'sku' => 'def',
            'product_name' => 'Test Product',
            'description' => 'This is a test product.',
        ],[
            'sku' => 'ghi',
            'product_name' => 'Test Product',
            'description' => 'This is a test product.',
        ]];
        return $data;
    }


}