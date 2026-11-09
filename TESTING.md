# Magento 2 MCP Server - Testing Guide

This guide details how to execute automated unit tests, integration tests, and live STDIO end-to-end tests for the Magento 2 Model Context Protocol Server.

**Author:** Priyank Jivani – Senior Magento 2 / Adobe Commerce Architect

---

## 1. Automated PHPUnit Test Suite

The repository includes a PHPUnit test suite covering JSON-RPC 2.0 message parsing, tool registry schema validation, server initialization, resource routing, and prompt dispatching.

To run the unit tests:

```bash
composer test
# Or directly via PHPUnit:
vendor/bin/phpunit -c phpunit.xml.dist
```

Expected output:
```text
PHPUnit 12.x.x by Sebastian Bergmann and contributors.

Runtime:       PHP 8.5.4
Configuration: phpunit.xml.dist

..........                                                      10 / 10 (100%)

Time: 00:00.004, Memory: 18.00 MB

OK (10 tests, 35 assertions)
```

---

## 2. Live System STDIO Test Harness

To execute the complete 59-test validation suite against a live Magento 2.4.9 installation:

```bash
# 1. Set environment variables
export MAGENTO_BASE_URL="http://152.67.31.72"
export MAGENTO_ACCESS_TOKEN="YOUR_ADMIN_JWT_BEARER_TOKEN"
export MAGENTO_STORE_CODE="default"
export MAGENTO_READ_ONLY="false"

# 2. Execute test runner
php run_full_mcp_qa_suite.sh
```

---

## 3. Manual Testing via STDIO Pipe

You can pipe standard JSON-RPC 2.0 messages directly to `bin/magento-mcp-server` over standard input.

### Initialize Handshake:
```bash
echo '{"jsonrpc":"2.0","id":1,"method":"initialize","params":{"protocolVersion":"2024-11-05","clientInfo":{"name":"CLI-Tester","version":"1.0.0"}}}' | bin/magento-mcp-server
```

### List Discovered Tools:
```bash
echo '{"jsonrpc":"2.0","id":2,"method":"tools/list","params":{}}' | bin/magento-mcp-server
```

### Call a Tool (`magento_get_version`):
```bash
echo '{"jsonrpc":"2.0","id":3,"method":"tools/call","params":{"name":"magento_get_version","arguments":{}}}' | bin/magento-mcp-server
```

### Call a Tool with Parameters (`magento_get_product`):
```bash
echo '{"jsonrpc":"2.0","id":4,"method":"tools/call","params":{"name":"magento_get_product","arguments":{"sku":"LUMA-WT01"}}}' | bin/magento-mcp-server
```

---

## 4. Continuous Integration (CI)

The GitHub Actions workflow `.github/workflows/ci.yml` automatically triggers on push and pull request across PHP versions 8.1, 8.2, 8.3, and 8.4, validating:
1. `composer.json` syntax and strict normalization.
2. PHP syntax linting across all source files (`php -l`).
3. Complete PHPUnit test execution.
