<?php

namespace App\Services;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductService
{
    public function __construct(private readonly EntityManagerInterface $entityManager,private readonly ValidatorInterface $validator)
    {
    }

    public function createRecords(array $data): array
    {
        $successRecords = [];
        $failedRecords = [];

        foreach ($data as $record) {
            try {
                $this->validateRecord($record);
                $product = new Product();
                $product->setSku($record['sku']);
                $product->setProductName($record['product_name'] ?? '');
                $product->setDescription($record['description'] ?? '');

                $this->entityManager->persist($product);
                $successRecords[] = $record['sku'];
            } catch (\Exception $e) {
                $failedRecords[] = json_decode($e->getMessage(), true);
            }
        }

        $this->entityManager->flush();

        return [
            'success_records' => $successRecords,
            'failed_records' => $failedRecords,
        ];
    }



    public function updateProducts(array $data): array
    {
        $successRecords = [];
        $failedRecords = [];

        foreach ($data as $record) {
            try {
                $this->processProductRecord($record, $successRecords);
            } catch (\Exception $e) {
                $failedRecords[] = json_decode($e->getMessage(), true);
            }
        }

        $this->entityManager->flush();

        return ['successRecords' => $successRecords, 'failedRecords' => $failedRecords];
    }

    private function processProductRecord(array $record, array &$successRecords): void
    {
        if (!isset($record['sku'])) {
            throw new \Exception('SKU must be defined for each record');
        }
        $sku = $record['sku'];
        $product = $this->entityManager->getRepository(Product::class)->findOneBy(['Sku' => $sku]);
        if (!$product) {
            throw new \Exception(json_encode(['error' => "Product with SKU $sku not found.", 'sku' => $sku]));
        }
        $product->setProductName($record['product_name'] ?? '');
        $product->setDescription($record['description'] ?? '');

        $violations = $this->validator->validate($product);
        if (count($violations) > 0) {
            $errorMessages = [];
            foreach ($violations as $violation) {
                $errorMessages[] = $violation->getMessage();
            }
            throw new \Exception(json_encode(['error' => $errorMessages, 'sku' => $sku]));
        }

        $this->entityManager->persist($product);
        $successRecords[] = $sku;
    }

    private function validateRecord(array $record): void
    {
        if (!isset($record['sku'])) {
            throw new \Exception('SKU must be defined for each record');
        }

        $product = new Product();
        $product->setSku($record['sku']);
        $product->setProductName($record['product_name'] ?? '');
        $product->setDescription($record['description'] ?? '');

        $violations = $this->validator->validate($product);

        if (count($violations) > 0) {
            $errorMessages = [];
            $sku = '';
            foreach ($violations as $violation) {
                $errorMessages[] = $violation->getMessage();
                $sku = $violation->getRoot()->getSku();
            }
            throw new \Exception(json_encode(['error' => $errorMessages, 'sku' => $sku]));
        }
    }
}