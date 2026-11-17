<?php
declare(strict_types=1);

namespace PriyankJivani\McpBridge\Model\Action;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\CatalogInventory\Api\StockRegistryInterface;

class InventoryHandler
{
    private ProductRepositoryInterface $productRepository;
    private StockRegistryInterface $stockRegistry;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        StockRegistryInterface $stockRegistry
    ) {
        $this->productRepository = $productRepository;
        $this->stockRegistry = $stockRegistry;
    }

    public function getStock(array $params): array
    {
        $sku = (string)($params['sku'] ?? '');
        if ($sku === '') {
            throw new \InvalidArgumentException('Product SKU is required.');
        }

        $stockItem = $this->stockRegistry->getStockItemBySku($sku);
        $product = $this->productRepository->get($sku);

        return [
            'sku' => $sku,
            'product_id' => (int)$product->getId(),
            'qty' => (float)$stockItem->getQty(),
            'is_in_stock' => (bool)$stockItem->getIsInStock(),
            'manage_stock' => (bool)$stockItem->getManageStock()
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

        $stockItem = $this->stockRegistry->getStockItemBySku($sku);
        $stockItem->setQty($qty);
        $stockItem->setIsInStock($isInStock);
        $this->stockRegistry->updateStockItemBySku($sku, $stockItem);

        return [
            'success' => true,
            'sku' => $sku,
            'qty' => $qty,
            'is_in_stock' => $isInStock
        ];
    }
}
