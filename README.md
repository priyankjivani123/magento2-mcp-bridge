# Magento 2 MCP Bridge (PriyankJivani_McpBridge)

[![Magento 2.4.x](https://img.shields.io/badge/Magento-2.4.x-orange.svg?logo=magento)](https://business.adobe.com/products/magento/magento-commerce.html)
[![PHP 8.1 - 8.4 Compatible](https://img.shields.io/badge/PHP-8.1%20%7C%208.2%20%7C%208.3%20%7C%208.4-777BB4.svg?logo=php)](https://www.php.net/)
[![Model Context Protocol](https://img.shields.io/badge/MCP-2024--11--05-blue.svg)](https://modelcontextprotocol.io/)
[![Composer Package](https://img.shields.io/badge/Composer-priyankjivani%2Fmodule--mcp--bridge-blue.svg?logo=composer)](https://packagist.org/)
[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
[![Upwork](https://img.shields.io/badge/Upwork-Available%20for%20Hire-14a800.svg?logo=upwork)](https://www.upwork.com/freelancers/priyankjivani)

The official **Magento 2 Model Context Protocol (MCP) Bridge** extension developed by **[Priyank Jivani](https://github.com/priyankjivani123)** (Senior Magento 2 Backend Developer & Architect).

This module securely connects Magento 2 / Adobe Commerce stores with Model Context Protocol (MCP) AI servers, enabling AI clients (such as **Claude Desktop**, **Cursor**, and custom autonomous AI agents) to safely interact with Magento using official **Service Contracts**, **Dependency Injection**, and robust **Role-Based Access Control (RBAC)**.

---

## 🏗 System Architecture

```
┌─────────────────────────────────┐
│ AI Clients (Claude / Cursor)   │
└────────────────┬────────────────┘
                 │ MCP JSON-RPC (STDIO / SSE)
┌────────────────▼────────────────┐
│  magento2-mcp-server            │
│  (Standalone PHP MCP Server)    │
└────────────────┬────────────────┘
                 │ Secure REST WebAPI (Bearer / Header Auth)
┌────────────────▼────────────────┐
│  PriyankJivani_McpBridge        │
│  - SecurityManager (RBAC)       │
│  - Service Contract Handlers    │
│  - Dry-Run & Confirmation Gate  │
│  - Audit Logger                 │
└────────────────┬────────────────┘
                 │ Official Magento Service Contracts
┌────────────────▼────────────────┐
│  Magento 2 / Adobe Commerce Core│
│  (Products, Orders, CMS, etc.)  │
└─────────────────────────────────┘
```

---

## 🚀 Key Features

* **Strict RBAC & Operation Levels:**
  * `READ_ONLY`: Catalog queries, order lookup, CMS inspection, store health, SEO diagnostics.
  * `WRITE`: Price/stock updates, CMS creation, order comments, cache flushing. Supports `dry_run=true` to preview impacts before writing!
  * `DESTRUCTIVE`: Product deletion, order cancellations. Requires explicit `confirm=true` and `force_destructive=true` confirmation.
* **Sensitive Data Protection:**
  * Configuration paths containing `password`, `secret`, `key`, `token`, or `crypt` are automatically masked.
  * Customer queries are PII-sanitized (hashed passwords and auth tokens are stripped).
* **Comprehensive Domain Coverage:**
  * **Catalog & Products:** List, search, get, update prices, update stock, delete with confirmation.
  * **Sales & Orders:** List orders, get details, order items, totals, append comments.
  * **Customers:** Search customers, list orders, addresses.
  * **Inventory:** Real-time stock status, MSI salable quantity checks.
  * **Developer Diagnostics:** Cache type status, indexer health, cron history, OPcache status, PHP configuration, error log inspection.
  * **Performance & SEO Audits:** Actionable performance findings and meta tag auditing.

---

## 🌐 Magento & PHP Compatibility Matrix

| Magento / Adobe Commerce Release | Supported PHP Versions | Compatibility Status | Technical Notes |
| :--- | :--- | :---: | :--- |
| **Magento 2.4.4 - 2.4.5-p1** | PHP 8.1 | ✅ Certified | Full service contract compatibility |
| **Magento 2.4.5-p2 - 2.4.6-pX** | PHP 8.1, PHP 8.2 | ✅ Certified | Tested on 2.4.6-p8 |
| **Magento 2.4.7 - 2.4.7-pX** | PHP 8.2, PHP 8.3 | ✅ Certified | Tested on 2.4.7-p3 |
| **Magento 2.4.8 (Roadmap)** | PHP 8.3, PHP 8.4 | 🚀 Forward-Compatible | Strict typing, no implicit nullables |
| **PHP 8.5 (Upcoming)** | *Pending Adobe Core* | ⏳ Preview / Audited | Codebase compiles cleanly under PHP 8.5 runtime |

---

## 📦 Composer Installation Instructions

### Step 1: Register GitHub VCS Repository
```bash
composer config repositories.priyankjivani-mcp-bridge vcs https://github.com/priyankjivani123/magento2-mcp-bridge.git
```

### Step 2: Require the Package
```bash
composer require priyankjivani/module-mcp-bridge:^1.0.0
```

### Step 3: Enable the Module & Run Setup Upgrade
```bash
bin/magento module:enable PriyankJivani_McpBridge
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento cache:flush
```

---

## ⚙️ Configuration

In your Magento 2 Admin panel, navigate to:  
**Stores > Configuration > Priyank Jivani Extensions > MCP AI Bridge**

* **Enable MCP Bridge:** Yes / No
* **Operation Mode:**
  * `Read-Only (Non-Destructive)`: Blocks all write and delete operations.
  * `Read / Write (Full Access with Safeguards)`: Permits authorized mutations with confirmation safeguards.
* **Secret Bridge Token:** Configure a dedicated secret token for `X-MCP-Bridge-Token` header authentication.
* **Enable Audit Logging:** Records all tool calls to `var/log/mcp_audit.log`.

---

## 🧪 Automated Testing

Run the automated PHPUnit test suite:
```bash
vendor/bin/phpunit -c phpunit.xml.dist
```

---

## 📋 Changelog

Please see [CHANGELOG.md](CHANGELOG.md).

---

## 📄 License

This module is open-source software licensed under the [MIT License](LICENSE).

---

## Developing a Magento 2 project?

I help businesses build, customize, optimize and maintain Magento 2 / Adobe Commerce stores.

**Available for:**
- Magento 2 Custom Development
- Magento MCP & AI Integrations
- Magento Extensions
- Bug Fixes and Troubleshooting
- Performance Optimization
- Hyvä Theme Development
- API Integrations
- Ongoing Magento Maintenance

**Hire me on Upwork:** [Priyank Jivani Upwork Profile](https://www.upwork.com/freelancers/priyankjivani)  
**GitHub:** [github.com/priyankjivani123](https://github.com/priyankjivani123)

Contact me for freelance projects and long-term collaborations.
