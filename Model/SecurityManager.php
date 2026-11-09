<?php
declare(strict_types=1);

namespace PriyankJivani\McpBridge\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Model\ScopeInterface;

class SecurityManager
{
    public const LEVEL_READ_ONLY = 'READ_ONLY';
    public const LEVEL_WRITE = 'WRITE';
    public const LEVEL_DESTRUCTIVE = 'DESTRUCTIVE';

    private ScopeConfigInterface $scopeConfig;

    /**
     * Mapping of tool action to security classification.
     */
    private array $actionLevels = [
        // Product
        'magento_list_products' => self::LEVEL_READ_ONLY,
        'magento_get_product' => self::LEVEL_READ_ONLY,
        'magento_search_products' => self::LEVEL_READ_ONLY,
        'magento_get_product_attributes' => self::LEVEL_READ_ONLY,
        'magento_get_product_media' => self::LEVEL_READ_ONLY,
        'magento_create_product' => self::LEVEL_WRITE,
        'magento_update_product' => self::LEVEL_WRITE,
        'magento_update_product_price' => self::LEVEL_WRITE,
        'magento_update_product_stock' => self::LEVEL_WRITE,
        'magento_bulk_update_products' => self::LEVEL_WRITE,
        'magento_manage_product_categories' => self::LEVEL_WRITE,
        'magento_delete_product' => self::LEVEL_DESTRUCTIVE,

        // Order
        'magento_list_orders' => self::LEVEL_READ_ONLY,
        'magento_get_order' => self::LEVEL_READ_ONLY,
        'magento_search_orders' => self::LEVEL_READ_ONLY,
        'magento_get_order_items' => self::LEVEL_READ_ONLY,
        'magento_get_order_status' => self::LEVEL_READ_ONLY,
        'magento_get_invoice' => self::LEVEL_READ_ONLY,
        'magento_get_shipment' => self::LEVEL_READ_ONLY,
        'magento_get_order_totals' => self::LEVEL_READ_ONLY,
        'magento_update_order_status' => self::LEVEL_WRITE,
        'magento_add_order_comment' => self::LEVEL_WRITE,

        // Customer
        'magento_list_customers' => self::LEVEL_READ_ONLY,
        'magento_get_customer' => self::LEVEL_READ_ONLY,
        'magento_search_customers' => self::LEVEL_READ_ONLY,
        'magento_get_customer_addresses' => self::LEVEL_READ_ONLY,
        'magento_get_customer_orders' => self::LEVEL_READ_ONLY,
        'magento_create_customer' => self::LEVEL_WRITE,
        'magento_update_customer' => self::LEVEL_WRITE,

        // Inventory
        'magento_get_stock' => self::LEVEL_READ_ONLY,
        'magento_get_sources' => self::LEVEL_READ_ONLY,
        'magento_list_stock_items' => self::LEVEL_READ_ONLY,
        'magento_check_salable_quantity' => self::LEVEL_READ_ONLY,
        'magento_update_stock' => self::LEVEL_WRITE,
        'magento_manage_source_items' => self::LEVEL_WRITE,
        'magento_bulk_inventory_update' => self::LEVEL_WRITE,

        // Category
        'magento_list_categories' => self::LEVEL_READ_ONLY,
        'magento_get_category' => self::LEVEL_READ_ONLY,
        'magento_get_category_tree' => self::LEVEL_READ_ONLY,
        'magento_create_category' => self::LEVEL_WRITE,
        'magento_update_category' => self::LEVEL_WRITE,
        'magento_manage_category_products' => self::LEVEL_WRITE,

        // CMS
        'magento_list_cms_pages' => self::LEVEL_READ_ONLY,
        'magento_get_cms_page' => self::LEVEL_READ_ONLY,
        'magento_list_cms_blocks' => self::LEVEL_READ_ONLY,
        'magento_get_cms_block' => self::LEVEL_READ_ONLY,
        'magento_create_cms_page' => self::LEVEL_WRITE,
        'magento_update_cms_page' => self::LEVEL_WRITE,
        'magento_update_cms_block' => self::LEVEL_WRITE,

        // Config
        'magento_get_store_config' => self::LEVEL_READ_ONLY,
        'magento_list_store_views' => self::LEVEL_READ_ONLY,
        'magento_get_website_information' => self::LEVEL_READ_ONLY,
        'magento_get_currency_configuration' => self::LEVEL_READ_ONLY,
        'magento_get_tax_configuration' => self::LEVEL_READ_ONLY,
        'magento_get_shipping_configuration' => self::LEVEL_READ_ONLY,
        'magento_get_payment_configuration' => self::LEVEL_READ_ONLY,

        // Dev Tools
        'magento_get_version' => self::LEVEL_READ_ONLY,
        'magento_check_module_status' => self::LEVEL_READ_ONLY,
        'magento_list_modules' => self::LEVEL_READ_ONLY,
        'magento_get_cache_status' => self::LEVEL_READ_ONLY,
        'magento_get_indexer_status' => self::LEVEL_READ_ONLY,
        'magento_get_cron_status' => self::LEVEL_READ_ONLY,
        'magento_get_cron_history' => self::LEVEL_READ_ONLY,
        'magento_get_deployment_status' => self::LEVEL_READ_ONLY,
        'magento_get_system_health' => self::LEVEL_READ_ONLY,
        'magento_get_php_information' => self::LEVEL_READ_ONLY,
        'magento_get_composer_information' => self::LEVEL_READ_ONLY,
        'magento_get_database_health' => self::LEVEL_READ_ONLY,
        'magento_get_opensearch_health' => self::LEVEL_READ_ONLY,
        'magento_get_redis_health' => self::LEVEL_READ_ONLY,
        'magento_get_varnish_status' => self::LEVEL_READ_ONLY,
        'magento_get_store_errors' => self::LEVEL_READ_ONLY,
        'magento_flush_cache' => self::LEVEL_WRITE,
        'magento_reindex' => self::LEVEL_WRITE,

        // Performance & SEO
        'magento_analyze_performance' => self::LEVEL_READ_ONLY,
        'magento_get_product_seo' => self::LEVEL_READ_ONLY,
        'magento_get_category_seo' => self::LEVEL_READ_ONLY,
        'magento_get_meta_information' => self::LEVEL_READ_ONLY,
        'magento_check_missing_meta' => self::LEVEL_READ_ONLY,
        'magento_check_duplicate_meta' => self::LEVEL_READ_ONLY,
        'magento_get_url_rewrites' => self::LEVEL_READ_ONLY,
        'magento_check_broken_urls' => self::LEVEL_READ_ONLY,
        'magento_get_canonical_configuration' => self::LEVEL_READ_ONLY,
        'magento_get_sitemap_status' => self::LEVEL_READ_ONLY,
        'magento_run_seo_audit' => self::LEVEL_READ_ONLY,
        'magento_update_product_seo' => self::LEVEL_WRITE,
        'magento_update_category_seo' => self::LEVEL_WRITE,

        // AI Store Intelligence
        'magento_generate_store_report' => self::LEVEL_READ_ONLY,
        'magento_generate_inventory_report' => self::LEVEL_READ_ONLY,
        'magento_generate_health_report' => self::LEVEL_READ_ONLY,
        'magento_diagnose_error' => self::LEVEL_READ_ONLY,
        'magento_audit_security' => self::LEVEL_READ_ONLY
    ];

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function getActionLevel(string $action): string
    {
        return $this->actionLevels[$action] ?? self::LEVEL_WRITE;
    }

    public function isReadOnlyMode(): bool
    {
        $mode = $this->scopeConfig->getValue('mcp_bridge/general/mode', ScopeInterface::SCOPE_STORE);
        return $mode === 'read_only';
    }

    public function isBridgeEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag('mcp_bridge/general/enabled', ScopeInterface::SCOPE_STORE);
    }

    public function validateExecution(string $action, bool $isDryRun, bool $isConfirm): void
    {
        if (!$this->isBridgeEnabled()) {
            throw new LocalizedException(__('Magento MCP Bridge is currently disabled.'));
        }

        $level = $this->getActionLevel($action);

        if ($this->isReadOnlyMode() && $level !== self::LEVEL_READ_ONLY) {
            throw new LocalizedException(__('Action %1 is blocked: Store is set to READ-ONLY mode.', $action));
        }

        if ($level === self::LEVEL_DESTRUCTIVE && !$isConfirm && !$isDryRun) {
            throw new LocalizedException(
                __('Action %1 is a DESTRUCTIVE operation. Explicit confirmation is required (set confirm=true or dry_run=true).', $action)
            );
        }
    }
}
