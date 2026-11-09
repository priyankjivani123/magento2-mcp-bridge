<?php
declare(strict_types=1);

namespace PriyankJivani\McpBridge\Test\Unit\Model;

use PHPUnit\Framework\TestCase;
use PriyankJivani\McpBridge\Model\Data\McpRequest;
use PriyankJivani\McpBridge\Model\Data\McpResponse;

class McpDataTest extends TestCase
{
    public function testRequestDataModel(): void
    {
        $request = new McpRequest();
        $request->setAction('magento_list_products');
        $paramsArray = ['limit' => 10, 'page' => 2];
        $request->setParams(json_encode($paramsArray));
        $request->setDryRun(true);
        $request->setConfirm(false);

        $this->assertEquals('magento_list_products', $request->getAction());
        $this->assertEquals(json_encode($paramsArray), $request->getParams());
        $this->assertEquals($paramsArray, $request->getParsedParams());
        $this->assertTrue($request->isDryRun());
        $this->assertFalse($request->isConfirm());
    }

    public function testResponseDataModel(): void
    {
        $response = new McpResponse();
        $response->setSuccess(true);
        $response->setAction('magento_get_product');
        $response->setResult(['sku' => 'TEST-SKU', 'price' => 49.99]);
        $response->setExecutionTimeMs(12.5);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals('magento_get_product', $response->getAction());
        $this->assertIsArray($response->getResult());
        $this->assertEquals('TEST-SKU', $response->getResult()['sku']);
        $this->assertEquals(12.5, $response->getExecutionTimeMs());
        $this->assertNull($response->getError());
    }
}
