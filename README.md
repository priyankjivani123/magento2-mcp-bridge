# Magento 2 Model Context Protocol (MCP) Bridge Module

[![Continuous Integration](https://github.com/priyankjivani123/magento2-mcp-bridge/actions/workflows/ci.yml/badge.svg)](https://github.com/priyankjivani123/magento2-mcp-bridge/actions/workflows/ci.yml)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)
[![Magento 2](https://img.shields.io/badge/Magento-2.4.9-ff5722.svg)](https://business.adobe.com/products/magento/magento-commerce.html)
[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D8.1-777bb4.svg)](https://www.php.net/)
[![Test Pass Rate](https://img.shields.io/badge/Tests-59%2F59%20(100%25)-brightgreen.svg)](QA-REPORT.md)

`PriyankJivani_McpBridge` is an enterprise-grade Adobe Commerce / Magento 2 extension that exposes a secure, high-performance WebAPI dispatch layer for Model Context Protocol (MCP) servers and autonomous AI agents.

**Author & Lead Architect:** [Priyank Jivani](https://github.com/priyankjivani123)  
**Live Client Showcase:** [http://152.67.31.72/mcp/](http://152.67.31.72/mcp/)  
**Public Demo Storefront:** [http://152.67.31.72/](http://152.67.31.72/)  
**Magento Admin:** [http://152.67.31.72/admin/](http://152.67.31.72/admin/) (`admin` / `PriyankAdmin123!`)  
**Companion MCP Server:** [magento2-mcp-server](https://github.com/priyankjivani123/magento2-mcp-server)

---

## ⚡ Module Capabilities

- **Unified WebAPI Endpoint:** `POST /rest/default/V1/mcp/dispatch` processes all 39 MCP tools via a single, optimized JSON API contract.
- **Service Contract Architecture:** Operates strictly through Magento 2 core repository interfaces (`ProductRepositoryInterface`, `CategoryRepositoryInterface`, `CustomerRepositoryInterface`, `OrderRepositoryInterface`).
- **Security & Permission Engine:** `SecurityManager` categorizes all actions into `READ_ONLY`, `WRITE`, and `DESTRUCTIVE`. Enforces store-level read-only mode and requires explicit confirmation (`confirm=true`) for destructive deletions.
- **Dry-Run Simulation:** Mutating actions support `dry_run=true` to preview catalog price adjustments, inventory changes, and cache flushes without persisting.
- **Concurrent Session Management:** Plugins prevent session eviction, allowing multiple clients to test the demo environment simultaneously.
- **PHP 8.5 Fully Compatible:** Custom deserializer handling avoids `settype()` strictness issues on PHP 8.5 while preserving strict types.

---

## 📦 Installation via Composer

```bash
composer require priyankjivani/module-mcp-bridge:^1.0
bin/magento module:enable PriyankJivani_McpBridge
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento cache:clean
```

---

## 🛠️ Complete MCP Tool Coverage (39 Actions)

See [MCP-TOOLS.md](MCP-TOOLS.md) for full action documentation and parameters.

- **Catalog:** `magento_list_products`, `magento_get_product`, `magento_search_products`, `magento_create_product`, `magento_update_product`, `magento_update_product_price`, `magento_delete_product`.
- **Orders:** `magento_list_orders`, `magento_get_order`, `magento_add_order_comment`.
- **Customers:** `magento_list_customers`, `magento_get_customer`, `magento_create_customer`.
- **Inventory:** `magento_get_stock`, `magento_update_stock`.
- **Categories & CMS:** `magento_get_category`, `magento_get_category_tree`, `magento_create_category`, `magento_list_cms_pages`, `magento_get_cms_page`, `magento_list_cms_blocks`, `magento_get_cms_block`.
- **Configuration:** `magento_get_store_config`.
- **Diagnostics:** `magento_get_version`, `magento_get_system_health`, `magento_get_php_information`, `magento_get_cache_status`, `magento_clean_cache`, `magento_get_indexer_status`, `magento_get_cron_status`, `magento_get_database_health`, `magento_get_opensearch_health`, `magento_get_redis_health`, `magento_get_store_errors`, `magento_list_modules`, `magento_generate_store_report`.
- **Performance & SEO:** `magento_analyze_performance`, `magento_get_product_seo`, `magento_run_seo_audit`.

---

## 📚 Documentation Index

- [Architecture & Module Design](ARCHITECTURE.md)
- [Complete Tool Catalog & Parameter Schemas](MCP-TOOLS.md)
- [Comprehensive QA Audit & Test Execution Report](QA-REPORT.md)
- [Security Audit & Penetration Testing](SECURITY-AUDIT.md)
- [Performance & Latency Benchmark Report](PERFORMANCE-REPORT.md)
- [Testing & Test Harness Guide](TESTING.md)
- [Troubleshooting & FAQ](TROUBLESHOOTING.md)
- [Changelog & Release Notes](CHANGELOG.md)

---

## 📄 License

This module is open-source software licensed under the [MIT License](LICENSE).
