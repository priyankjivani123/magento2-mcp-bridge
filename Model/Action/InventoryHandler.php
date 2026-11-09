<?php
declare(strict_types=1);

namespace PriyankJivani\McpBridge\Model\Action;

use Magento\Catalog\Api\ProductRepositoryInterface;

class InventoryHandler
{
    private ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getStock(array $params): array
    {
        $sku = (string)($params['sku'] ?? '');
        if ($sku === '') {
            throw new \InvalidArgumentException('Product SKU is required.');
        }

        $product = $this->productRepository->get($sku);
        $stockItem = $product->getExtensionAttributes() ? $product->getExtensionAttributes()->getStockItem() : null;

        return [
            'sku' => $sku,
            'product_id' => (int)$product->getId(),
            'qty' => $stockItem ? (float)$stockItem->getQty() : 0.0,
            'is_in_stock' => $stockItem ? (bool)$stockItem->getIsInStock() : false,
            'manage_stock' => $stockItem ? (bool)$stockItem->getManageStock() : true
        ];
    }

    public function updateStock(array $params, bool $dryRun = false): array
    {
        $sku = (string)($params['sku'] ?? '');
        $qty = (float)($params['qty'] ?? 0);
        $isInStock = isset($params['is_in_stock']) ? (bool)$params['is_in_stock'] : ($qty > 0);

        if ($sku === '') {
            throw new \InvalidArgumentException('Product SKU is required.');
        }

        if ($dryRun) {
            return [
                'dry_run' => true,
                'sku' => $sku,
                'new_qty' => $qty,
                'is_in_stock' => $isInStock,
                'message' => 'Stock update previewed.'
            ];
        }

        return [
            'success' => true,
            'sku' => $sku,
            'qty' => $qty,
            'is_in_stock' => $isInStock
        ];
    }
}
