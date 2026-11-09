# Magento 2 MCP Server - Complete Tool Inventory & Reference

This document provides exhaustive documentation for all 39 Model Context Protocol tools implemented in `PriyankJivani\McpServer`.

**Author:** Priyank Jivani – Senior Magento 2 / Adobe Commerce Architect  
**Protocol Version:** `2024-11-05`  
**Classification:** `READ_ONLY` | `WRITE` | `DESTRUCTIVE`

---

## 1. Catalog Management

### `magento_list_products`
- **Classification:** `READ_ONLY`
- **Description:** List catalog products with pagination, category filter, and status filters.
- **Parameters:**
  - `page` *(integer, optional)*: Page number (default: 1)
  - `limit` *(integer, optional)*: Page size (default: 20, max: 100)
  - `type_id` *(string, optional)*: Filter by type (`simple`, `configurable`, `virtual`, etc.)
- **Output:** Total count, current page, and array of product entities with ID, SKU, name, price, status, visibility.

### `magento_get_product`
- **Classification:** `READ_ONLY`
- **Description:** Get complete product entity details, prices, attributes, stock, and media by SKU.
- **Parameters:**
  - `sku` *(string, required)*: Unique product SKU
- **Output:** Entity details including special price, weight, category IDs, meta tags, and created/updated timestamps.

### `magento_search_products`
- **Classification:** `READ_ONLY`
- **Description:** Search catalog products by name, SKU, or keyword.
- **Parameters:**
  - `query` *(string, required)*: Search keyword
  - `limit` *(integer, optional)*: Max results to return (default: 20)
- **Output:** Matched items with SKU, name, price, and type ID.

### `magento_create_product`
- **Classification:** `WRITE`
- **Description:** Create a new catalog product with SKU, name, price, stock, and attributes.
- **Parameters:**
  - `sku` *(string, required)*: Unique SKU
  - `name` *(string, required)*: Display name
  - `price` *(number, required)*: Regular price
  - `type_id` *(string, optional)*: `simple` or `virtual` (default: `simple`)
  - `qty` *(number, optional)*: Initial stock quantity (default: 100)
  - `status` *(integer, optional)*: 1 = Enabled, 2 = Disabled (default: 1)
  - `visibility` *(integer, optional)*: 4 = Catalog & Search (default: 4)
  - `dry_run` *(boolean, optional)*: Simulate creation without saving
- **Output:** Saved product ID, SKU, name, price, and confirmation status.

### `magento_update_product`
- **Classification:** `WRITE`
- **Description:** Update core attributes of an existing product (name, description, visibility, status).
- **Parameters:**
  - `sku` *(string, required)*: Product SKU
  - `name` *(string, optional)*: New product name
  - `price` *(number, optional)*: New price
  - `status` *(integer, optional)*: New status
  - `visibility` *(integer, optional)*: New visibility
  - `description` *(string, optional)*: New HTML description
  - `dry_run` *(boolean, optional)*: Simulate update without saving
- **Output:** Updated entity properties.

### `magento_update_product_price`
- **Classification:** `WRITE`
- **Description:** Update regular or special price for a product. Supports dry_run.
- **Parameters:**
  - `sku` *(string, required)*: Product SKU
  - `price` *(number, required)*: New regular price
  - `special_price` *(number, optional)*: Promotional discounted price
  - `dry_run` *(boolean, optional)*: Preview price adjustment without persisting
- **Output:** Old price, new price, special price, and status.

### `magento_delete_product`
- **Classification:** `DESTRUCTIVE`
- **Description:** Permanently delete a product from the catalog. Requires confirm=true safeguard.
- **Parameters:**
  - `sku` *(string, required)*: Product SKU
  - `confirm` *(boolean, required)*: Safety safeguard flag (must be `true`)
- **Output:** Deletion confirmation and deleted entity ID.

---

## 2. Order Management

### `magento_list_orders`
- **Classification:** `READ_ONLY`
- **Description:** List sales orders with pagination, status filters, and customer email filter.
- **Parameters:**
  - `page` *(integer, optional)*: Page number
  - `limit` *(integer, optional)*: Orders per page
  - `status` *(string, optional)*: Order status (`pending`, `processing`, `complete`)

### `magento_get_order`
- **Classification:** `READ_ONLY`
- **Description:** Get complete sales order details including line items, totals, billing, and status history.
- **Parameters:**
  - `increment_id` *(string, optional)*: Order increment number (e.g. `000000001`)
  - `order_id` *(integer, optional)*: Internal entity ID

### `magento_add_order_comment`
- **Classification:** `WRITE`
- **Description:** Add an internal memo or customer status comment to an order.
- **Parameters:**
  - `order_id` *(integer, required)*: Order entity ID
  - `comment` *(string, required)*: Message content
  - `notify` *(boolean, optional)*: Send email notification to customer (default: `false`)
  - `visible_on_front` *(boolean, optional)*: Display comment in customer account

---

## 3. Customer Management

### `magento_list_customers`
- **Classification:** `READ_ONLY`
- **Description:** List store customers with pagination (PII sanitized).
- **Parameters:** `page`, `limit`

### `magento_get_customer`
- **Classification:** `READ_ONLY`
- **Description:** Get customer account details by customer ID (PII sanitized).
- **Parameters:** `customer_id` *(integer, required)*

### `magento_create_customer`
- **Classification:** `WRITE`
- **Description:** Create a new customer account in the store.
- **Parameters:**
  - `email` *(string, required)*: Customer email address
  - `firstname` *(string, required)*: First name
  - `lastname` *(string, required)*: Last name
  - `website_id` *(integer, optional)*: Target website ID (default: 1)
  - `dry_run` *(boolean, optional)*: Simulation preview

---

## 4. Inventory Management

### `magento_get_stock`
- **Classification:** `READ_ONLY`
- **Description:** Get real-time stock item quantity, salable status, and backorder status.
- **Parameters:** `sku` *(string, required)*

### `magento_update_stock`
- **Classification:** `WRITE`
- **Description:** Update product inventory quantity and stock status. Supports dry_run.
- **Parameters:**
  - `sku` *(string, required)*: Product SKU
  - `qty` *(number, required)*: Physical stock quantity
  - `is_in_stock` *(boolean, optional)*: Stock status flag
  - `dry_run` *(boolean, optional)*: Simulation mode

---

## 5. Category & CMS Management

### `magento_get_category`
- **Classification:** `READ_ONLY`
- **Description:** Get category details, active status, parent ID, and assigned product count.
- **Parameters:** `category_id` *(integer, required)*

### `magento_get_category_tree`
- **Classification:** `READ_ONLY`
- **Description:** Get store category hierarchy tree and assigned subcategory breakdown.
- **Parameters:** `root_id` *(integer, optional)*: Default is 2.

### `magento_create_category`
- **Classification:** `WRITE`
- **Description:** Create a new category in the catalog tree.
- **Parameters:**
  - `name` *(string, required)*: Category title
  - `parent_id` *(integer, optional)*: Parent category ID (default: 2)
  - `is_active` *(boolean, optional)*: Active status (default: true)
  - `dry_run` *(boolean, optional)*: Simulation mode

### `magento_list_cms_pages` & `magento_get_cms_page`
- **Classification:** `READ_ONLY`
- **Description:** Enumerate and inspect CMS static pages.

### `magento_list_cms_blocks` & `magento_get_cms_block`
- **Classification:** `READ_ONLY`
- **Description:** Enumerate and inspect CMS static content blocks.

---

## 6. Store Configuration

### `magento_get_store_config`
- **Classification:** `READ_ONLY`
- **Description:** Get store configuration value by XML path. Automatically masks sensitive credentials.
- **Parameters:** `path` *(string, required)*: e.g. `web/unsecure/base_url`

---

## 7. Developer Diagnostics & System Health

### `magento_get_version`
- **Classification:** `READ_ONLY`
- **Description:** Get Magento edition, bridge version, PHP version, and server environment.

### `magento_get_system_health`
- **Classification:** `READ_ONLY`
- **Description:** Inspect memory limit, OPcache status, disk space, and health score.

### `magento_get_php_information`
- **Classification:** `READ_ONLY`
- **Description:** Inspect PHP runtime configuration, extensions count, and upload limits.

### `magento_get_cache_status` & `magento_clean_cache`
- **Classification:** `READ_ONLY` / `WRITE`
- **Description:** Inspect all 15 Magento cache types and flush/clean specific types or ALL.

### `magento_get_indexer_status`
- **Classification:** `READ_ONLY`
- **Description:** Inspect status (valid/invalid) and mode for all 10 core Magento indexers.

### `magento_get_cron_status`
- **Classification:** `READ_ONLY`
- **Description:** Retrieve recent scheduled, running, and finished jobs from `cron_schedule`.

### `magento_get_database_health`, `magento_get_opensearch_health`, `magento_get_redis_health`
- **Classification:** `READ_ONLY`
- **Description:** Live health and reachability checks for MariaDB, OpenSearch 2.18, and Redis 8.0.

### `magento_get_store_errors` & `magento_list_modules`
- **Classification:** `READ_ONLY`
- **Description:** Read recent lines from `var/log/system.log` and verify module registrations.

### `magento_generate_store_report`
- **Classification:** `READ_ONLY`
- **Description:** Generates an executive health report summarizing store operational status.

---

## 8. SEO & Performance

### `magento_analyze_performance`
- **Classification:** `READ_ONLY`
- **Description:** Comprehensive technical performance audit across FPC, Varnish, Redis, database, and OPcache.

### `magento_get_product_seo` & `magento_run_seo_audit`
- **Classification:** `READ_ONLY`
- **Description:** Audit product meta tags and store-wide canonical link configurations.
