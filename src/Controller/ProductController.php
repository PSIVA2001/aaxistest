<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\ProductService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/records')]
class ProductController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/create', methods: ['POST'])]
    public function loadRecords(Request $request, ProductService $productService): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            if (empty($data)) {
                throw new \Exception('Invalid payload. JSON decoded to an empty array.');
            }
            $result = $productService->createRecords($data);
            $response = [
                'message' => count($result['success_records']) . ' record(s) loaded successfully',
            ];
            if (!empty($result['failed_records'])) {
                $response['failed_records'] = $result['failed_records'];
            }
            if (count($result['success_records']) > 0) {
                $response['message'] = count($result['success_records']) . ' record(s) updated successfully';
            } else {
                return new JsonResponse($response['failed_records'], Response::HTTP_BAD_REQUEST);
            }

            $status = empty($response['failed_records']) ? Response::HTTP_CREATED : Response::HTTP_MULTI_STATUS;
            return new JsonResponse($response, $status);

        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }


    #[Route('/update', methods: ['PUT'])]
    public function updateRecords(Request $request, ProductService $productUpdateService): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            if (empty($data)) {
                throw new \Exception('Invalid payload. JSON decoded to an empty array.');
            }
            $result = $productUpdateService->updateProducts($data);
            $successRecords = $result['successRecords'];
            $failedRecords = $result['failedRecords'];
            $response = [];
            if (count($successRecords) > 0) {
                $response['message'] = count($successRecords) . ' record(s) updated successfully';
            } else {
                return new JsonResponse($failedRecords, Response::HTTP_BAD_REQUEST);
            }

            if (!empty($failedRecords)) {
                $response['failed_records'] = $failedRecords;
            }

            $status = empty($failedRecords) ? Response::HTTP_CREATED : Response::HTTP_MULTI_STATUS;

            return new JsonResponse($response, $status);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/list',methods: ['GET'])]
    public function listProducts(): JsonResponse
    {
        try {
            $products = $this->entityManager->getRepository(Product::class)->findAll();
            $productList = [];
            foreach ($products as $product) {
                $productList[] = [
                    'sku' => $product->getSku(),
                    'product_name' => $product->getProductName(),
                    'description' => $product->getDescription(),
                    'created_at' => $product->getCreatedAt()->format('Y-m-d H:i:s'),
                    'updated_at' => $product->getUpdatedAt()->format('Y-m-d H:i:s'),
                ];
            }
            return new JsonResponse($productList, Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
