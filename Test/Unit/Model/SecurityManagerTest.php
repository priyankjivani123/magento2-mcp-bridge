<?php
declare(strict_types=1);

namespace PriyankJivani\McpBridge\Test\Unit\Model;

use PHPUnit\Framework\TestCase;
use PriyankJivani\McpBridge\Model\SecurityManager;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\LocalizedException;

class SecurityManagerTest extends TestCase
{
    private $scopeConfigMock;
    private SecurityManager $securityManager;

    protected function setUp(): void
    {
        $this->scopeConfigMock = $this->createMock(ScopeConfigInterface::class);
        $this->securityManager = new SecurityManager($this->scopeConfigMock);
    }

    public function testActionLevels(): void
    {
        $this->assertEquals(SecurityManager::LEVEL_READ_ONLY, $this->securityManager->getActionLevel('magento_list_products'));
        $this->assertEquals(SecurityManager::LEVEL_READ_ONLY, $this->securityManager->getActionLevel('magento_get_product'));
        $this->assertEquals(SecurityManager::LEVEL_WRITE, $this->securityManager->getActionLevel('magento_update_product_price'));
        $this->assertEquals(SecurityManager::LEVEL_DESTRUCTIVE, $this->securityManager->getActionLevel('magento_delete_product'));
    }

    public function testReadOnlyValidationAllowsReadOnlyAction(): void
    {
        $this->scopeConfigMock->method('isSetFlag')->willReturn(true);
        $this->scopeConfigMock->method('getValue')->willReturn('read_only');

        $this->securityManager->validateExecution('magento_list_products', false, false);
        $this->assertTrue(true); // Reached without exception
    }

    public function testReadOnlyValidationBlocksWriteAction(): void
    {
        $this->expectException(LocalizedException::class);
        $this->expectExceptionMessageMatches('/Store is set to READ-ONLY mode/');

        $this->scopeConfigMock->method('isSetFlag')->willReturn(true);
        $this->scopeConfigMock->method('getValue')->willReturn('read_only');

        $this->securityManager->validateExecution('magento_update_product_price', false, false);
    }

    public function testDestructiveActionRequiresConfirmation(): void
    {
        $this->expectException(LocalizedException::class);
        $this->expectExceptionMessageMatches('/DESTRUCTIVE operation/');

        $this->scopeConfigMock->method('isSetFlag')->willReturn(true);
        $this->scopeConfigMock->method('getValue')->willReturn('read_write');

        $this->securityManager->validateExecution('magento_delete_product', false, false);
    }

    public function testDestructiveActionAllowedWithConfirmation(): void
    {
        $this->scopeConfigMock->method('isSetFlag')->willReturn(true);
        $this->scopeConfigMock->method('getValue')->willReturn('read_write');

        $this->securityManager->validateExecution('magento_delete_product', false, true);
        $this->assertTrue(true);
    }
}
