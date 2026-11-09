<?php
declare(strict_types=1);

namespace PriyankJivani\McpBridge\Model;

use PriyankJivani\McpBridge\Api\McpDispatcherInterface;
use PriyankJivani\McpBridge\Api\Data\McpRequestInterface;
use PriyankJivani\McpBridge\Api\Data\McpResponseInterface;
use PriyankJivani\McpBridge\Model\Data\McpResponseFactory;
use PriyankJivani\McpBridge\Model\SecurityManager;
use PriyankJivani\McpBridge\Model\Action\ProductHandler;
use PriyankJivani\McpBridge\Model\Action\OrderHandler;
use PriyankJivani\McpBridge\Model\Action\CustomerHandler;
use PriyankJivani\McpBridge\Model\Action\InventoryHandler;
use PriyankJivani\McpBridge\Model\Action\CategoryHandler;
use PriyankJivani\McpBridge\Model\Action\CmsHandler;
use PriyankJivani\McpBridge\Model\Action\ConfigHandler;
use PriyankJivani\McpBridge\Model\Action\DevToolsHandler;
use PriyankJivani\McpBridge\Model\Action\PerformanceHandler;
use PriyankJivani\McpBridge\Model\Action\SeoHandler;

class McpDispatcher implements McpDispatcherInterface
{
    private SecurityManager $securityManager;
    private McpResponseFactory $responseFactory;
    private ProductHandler $productHandler;
    private OrderHandler $orderHandler;
    private CustomerHandler $customerHandler;
    private InventoryHandler $inventoryHandler;
    private CategoryHandler $categoryHandler;
    private CmsHandler $cmsHandler;
    private ConfigHandler $configHandler;
    private DevToolsHandler $devToolsHandler;
    private PerformanceHandler $performanceHandler;
    private SeoHandler $seoHandler;

    public function __construct(
        SecurityManager $securityManager,
        McpResponseFactory $responseFactory,
        ProductHandler $productHandler,
        OrderHandler $orderHandler,
        CustomerHandler $customerHandler,
        InventoryHandler $inventoryHandler,
        CategoryHandler $categoryHandler,
        CmsHandler $cmsHandler,
        ConfigHandler $configHandler,
        DevToolsHandler $devToolsHandler,
        PerformanceHandler $performanceHandler,
        SeoHandler $seoHandler
    ) {
        $this->securityManager = $securityManager;
        $this->responseFactory = $responseFactory;
        $this->productHandler = $productHandler;
        $this->orderHandler = $orderHandler;
        $this->customerHandler = $customerHandler;
        $this->inventoryHandler = $inventoryHandler;
        $this->categoryHandler = $categoryHandler;
        $this->cmsHandler = $cmsHandler;
        $this->configHandler = $configHandler;
        $this->devToolsHandler = $devToolsHandler;
        $this->performanceHandler = $performanceHandler;
        $this->seoHandler = $seoHandler;
    }

    public function dispatch(McpRequestInterface $request): McpResponseInterface
    {
        $startTime = microtime(true);
        $action = $request->getAction();
        $params = $request->getParams();
        $dryRun = $request->isDryRun();
        $confirm = $request->isConfirm();

        /** @var McpResponseInterface $response */
        $response = $this->responseFactory->create();
        $response->setAction($action);

        try {
            $this->securityManager->validateExecution($action, $dryRun, $confirm);

            $result = match ($action) {
                // Product
                'magento_list_products' => $this->productHandler->listProducts($params),
                'magento_get_product' => $this->productHandler->getProduct($params),
                'magento_search_products' => $this->productHandler->searchProducts($params),
                'magento_update_product_price' => $this->productHandler->updatePrice($params, $dryRun),
                'magento_delete_product' => $this->productHandler->deleteProduct($params, $confirm),

                // Order
                'magento_list_orders' => $this->orderHandler->listOrders($params),
                'magento_get_order' => $this->orderHandler->getOrder($params),
                'magento_add_order_comment' => $this->orderHandler->addComment($params),

                // Customer
                'magento_list_customers' => $this->customerHandler->listCustomers($params),
                'magento_get_customer' => $this->customerHandler->getCustomer($params),

                // Inventory
                'magento_get_stock' => $this->inventoryHandler->getStock($params),
                'magento_update_stock' => $this->inventoryHandler->updateStock($params, $dryRun),

                // Category & CMS
                'magento_get_category' => $this->categoryHandler->getCategory($params),
                'magento_get_cms_page' => $this->cmsHandler->getCmsPage($params),
                'magento_get_cms_block' => $this->cmsHandler->getCmsBlock($params),

                // Store Configuration
                'magento_get_store_config' => $this->configHandler->getStoreConfig($params),

                // Developer Tools
                'magento_get_version' => $this->devToolsHandler->getVersion(),
                'magento_get_system_health' => $this->devToolsHandler->getSystemHealth(),
                'magento_get_php_information' => $this->devToolsHandler->getPhpInformation(),

                // Performance & SEO
                'magento_analyze_performance' => $this->performanceHandler->analyzePerformance(),
                'magento_get_product_seo' => $this->seoHandler->getProductSeo($params),
                'magento_run_seo_audit' => $this->seoHandler->runSeoAudit(),

                default => throw new \InvalidArgumentException("Unsupported MCP action: $action")
            };

            $response->setSuccess(true);
            $response->setResult($result);
        } catch (\Throwable $e) {
            $response->setSuccess(false);
            $response->setError($e->getMessage());
        }

        $durationMs = round((microtime(true) - $startTime) * 1000, 2);
        $response->setExecutionTimeMs($durationMs);

        return $response;
    }

    public function ping(): array
    {
        return [
            'status' => 'OK',
            'mcp_bridge' => 'PriyankJivani_McpBridge',
            'version' => '1.0.0',
            'timestamp' => date('c'),
            'protocol' => '2024-11-05'
        ];
    }
}
