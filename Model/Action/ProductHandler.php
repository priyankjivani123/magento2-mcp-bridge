<?php
declare(strict_types=1);

namespace PriyankJivani\McpBridge\Model\Action;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Registry;
use Magento\Framework\Exception\NoSuchEntityException;

class ProductHandler
{
    private ProductRepositoryInterface $productRepository;
    private SearchCriteriaBuilder $searchCriteriaBuilder;
    private ProductFactory $productFactory;
    private Registry $registry;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        ProductFactory $productFactory,
        Registry $registry
    ) {
        $this->productRepository = $productRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->productFactory = $productFactory;
        $this->registry = $registry;
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
            'visibility' => (int)$product->getVisibility(),
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

    public function createProduct(array $params, bool $dryRun = false): array
    {
        $sku = trim((string)($params['sku'] ?? ''));
        $name = trim((string)($params['name'] ?? ''));
        $price = (float)($params['price'] ?? 0);
        $typeId = (string)($params['type_id'] ?? 'simple');
        $qty = (float)($params['qty'] ?? 100);
        $attributeSetId = (int)($params['attribute_set_id'] ?? 4);

        if ($sku === '' || $name === '') {
            throw new \InvalidArgumentException('sku and name are required.');
        }

        if ($dryRun) {
            return [
                'dry_run' => true,
                'sku' => $sku,
                'name' => $name,
                'price' => $price,
                'type_id' => $typeId,
                'qty' => $qty,
                'message' => 'Product creation previewed successfully.'
            ];
        }

        $product = $this->productFactory->create();
        $product->setSku($sku);
        $product->setName($name);
        $product->setPrice($price);
        $product->setTypeId($typeId);
        $product->setAttributeSetId($attributeSetId);
        $product->setStatus((int)($params['status'] ?? 1));
        $product->setVisibility((int)($params['visibility'] ?? 4));
        $product->setWeight((float)($params['weight'] ?? 1.0));
        
        if (!empty($params['description'])) {
            $product->setCustomAttribute('description', $params['description']);
        }
        if (!empty($params['short_description'])) {
            $product->setCustomAttribute('short_description', $params['short_description']);
        }

        $product->setStockData([
            'use_config_manage_stock' => 1,
            'is_in_stock' => $qty > 0 ? 1 : 0,
            'qty' => $qty
        ]);

        $saved = $this->productRepository->save($product);

        return [
            'success' => true,
            'id' => (int)$saved->getId(),
            'sku' => $saved->getSku(),
            'name' => $saved->getName(),
            'price' => (float)$saved->getPrice(),
            'status' => (int)$saved->getStatus(),
            'visibility' => (int)$saved->getVisibility()
        ];
    }

    public function updateProduct(array $params, bool $dryRun = false): array
    {
        $sku = (string)($params['sku'] ?? '');
        if ($sku === '') {
            throw new \InvalidArgumentException('Product SKU is required.');
        }

        $product = $this->productRepository->get($sku);

        if ($dryRun) {
            return [
                'dry_run' => true,
                'sku' => $sku,
                'preview_updates' => $params,
                'message' => 'Product update simulated successfully.'
            ];
        }

        if (isset($params['name'])) {
            $product->setName((string)$params['name']);
        }
        if (isset($params['price'])) {
            $product->setPrice((float)$params['price']);
        }
        if (isset($params['status'])) {
            $product->setStatus((int)$params['status']);
        }
        if (isset($params['visibility'])) {
            $product->setVisibility((int)$params['visibility']);
        }
        if (isset($params['description'])) {
            $product->setCustomAttribute('description', $params['description']);
        }

        $saved = $this->productRepository->save($product);

        return [
            'success' => true,
            'sku' => $saved->getSku(),
            'name' => $saved->getName(),
            'price' => (float)$saved->getPrice(),
            'status' => (int)$saved->getStatus()
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

        if (!$this->registry->registry('isSecureArea')) {
            $this->registry->register('isSecureArea', true);
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
