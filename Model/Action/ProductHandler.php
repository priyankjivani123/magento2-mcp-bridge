<?php
declare(strict_types=1);

namespace PriyankJivani\McpBridge\Model\Action;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\NoSuchEntityException;

class ProductHandler
{
    private ProductRepositoryInterface $productRepository;
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->productRepository = $productRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    public function listProducts(array $params): array
    {
        $page = (int)($params['page'] ?? 1);
        $limit = min((int)($params['limit'] ?? 20), 100);

        $this->searchCriteriaBuilder->setCurrentPage($page);
        $this->searchCriteriaBuilder->setPageSize($limit);

        if (!empty($params['type_id'])) {
            $this->searchCriteriaBuilder->addFilter('type_id', $params['type_id']);
        }
        if (isset($params['status'])) {
            $this->searchCriteriaBuilder->addFilter('status', (int)$params['status']);
        }

        $criteria = $this->searchCriteriaBuilder->create();
        $searchResults = $this->productRepository->getList($criteria);

        $items = [];
        foreach ($searchResults->getItems() as $product) {
            $items[] = [
                'id' => (int)$product->getId(),
                'sku' => $product->getSku(),
                'name' => $product->getName(),
                'price' => (float)$product->getPrice(),
                'type_id' => $product->getTypeId(),
                'status' => (int)$product->getStatus(),
                'visibility' => (int)$product->getVisibility(),
                'created_at' => $product->getCreatedAt()
            ];
        }

        return [
            'total_count' => $searchResults->getTotalCount(),
            'page' => $page,
            'limit' => $limit,
            'items' => $items
        ];
    }

    public function getProduct(array $params): array
    {
        $sku = (string)($params['sku'] ?? '');
        if ($sku === '') {
            throw new \InvalidArgumentException('Product SKU is required.');
        }

        $product = $this->productRepository->get($sku);

        return [
            'id' => (int)$product->getId(),
            'sku' => $product->getSku(),
            'name' => $product->getName(),
            'type_id' => $product->getTypeId(),
            'price' => (float)$product->getPrice(),
            'special_price' => $product->getSpecialPrice() ? (float)$product->getSpecialPrice() : null,
            'status' => (int)$product->getStatus(),
            'weight' => (float)$product->getWeight(),
            'category_ids' => $product->getCategoryIds(),
            'meta_title' => $product->getMetaTitle(),
            'meta_keyword' => $product->getMetaKeyword(),
            'meta_description' => $product->getMetaDescription(),
            'created_at' => $product->getCreatedAt(),
            'updated_at' => $product->getUpdatedAt()
        ];
    }

    public function searchProducts(array $params): array
    {
        $query = (string)($params['query'] ?? '');
        $limit = min((int)($params['limit'] ?? 20), 100);

        $this->searchCriteriaBuilder->addFilter('name', '%' . $query . '%', 'like');
        $this->searchCriteriaBuilder->setPageSize($limit);

        $criteria = $this->searchCriteriaBuilder->create();
        $results = $this->productRepository->getList($criteria);

        $items = [];
        foreach ($results->getItems() as $product) {
            $items[] = [
                'id' => (int)$product->getId(),
                'sku' => $product->getSku(),
                'name' => $product->getName(),
                'price' => (float)$product->getPrice(),
                'type_id' => $product->getTypeId()
            ];
        }

        return [
            'query' => $query,
            'total_count' => $results->getTotalCount(),
            'items' => $items
        ];
    }

    public function updatePrice(array $params, bool $dryRun = false): array
    {
        $sku = (string)($params['sku'] ?? '');
        $price = (float)($params['price'] ?? 0);
        $specialPrice = isset($params['special_price']) ? (float)$params['special_price'] : null;

        if ($sku === '') {
            throw new \InvalidArgumentException('Product SKU is required.');
        }

        $product = $this->productRepository->get($sku);
        $oldPrice = (float)$product->getPrice();

        if ($dryRun) {
            return [
                'dry_run' => true,
                'sku' => $sku,
                'old_price' => $oldPrice,
                'new_price' => $price,
                'special_price' => $specialPrice,
                'message' => 'Price update previewed successfully.'
            ];
        }

        $product->setPrice($price);
        if ($specialPrice !== null) {
            $product->setSpecialPrice($specialPrice);
        }
        $this->productRepository->save($product);

        return [
            'success' => true,
            'sku' => $sku,
            'old_price' => $oldPrice,
            'new_price' => $price,
            'special_price' => $specialPrice
        ];
    }

    public function deleteProduct(array $params, bool $confirm = false): array
    {
        $sku = (string)($params['sku'] ?? '');
        if ($sku === '') {
            throw new \InvalidArgumentException('Product SKU is required.');
        }

        if (!$confirm) {
            throw new \InvalidArgumentException('Explicit confirmation (confirm=true) is required to delete a product.');
        }

        $product = $this->productRepository->get($sku);
        $id = $product->getId();
        $this->productRepository->delete($product);

        return [
            'success' => true,
            'deleted_sku' => $sku,
            'deleted_id' => $id,
            'message' => 'Product permanently deleted.'
        ];
    }
}
