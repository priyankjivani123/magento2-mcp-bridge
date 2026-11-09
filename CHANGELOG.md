# Changelog

All notable changes to `priyankjivani/module-mcp-bridge` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [1.0.3] - 2026-11-09

### Added
- Expanded bridge actions to 39 enterprise-grade tools.
- Added `magento_create_product` and `magento_update_product` with automatic secure area registration.
- Added `magento_get_category_tree` and `magento_create_category`.
- Added `magento_create_customer`.
- Added `magento_list_cms_pages` and `magento_list_cms_blocks`.
- Added full diagnostic handlers: cache type status, cache cleaning, indexer status, cron schedule inspector, database health, OpenSearch cluster health, Redis latency, and error log retrieval.
- Added complete documentation suite: `TESTING.md`, `QA-REPORT.md`, `SECURITY-AUDIT.md`, `PERFORMANCE-REPORT.md`, `MCP-TOOLS.md`, `ARCHITECTURE.md`, `TROUBLESHOOTING.md`.

### Fixed
- Fixed PHP 8.5 `settype()` type processor error by passing JSON-encoded strings for parameter data transfers.
- Fixed PHP 8.5 `curl_close()` deprecation in `DevToolsHandler`.
- Added multi-session concurrent admin login plugin to prevent session eviction.
- Ensured `isSecureArea` registry setting is applied during entity deletions in WebAPI.

---

## [1.0.2] - 2026-11-09

### Added
- Multi-session concurrency plugins for admin sessions.
- Dedicated 10-year admin JWT token generation support.

---

## [1.0.1] - 2026-11-09

### Added
- SecurityManager action permission levels (`READ_ONLY`, `WRITE`, `DESTRUCTIVE`).
- Dry-run simulation support for price updates and inventory updates.

---

## [1.0.0] - 2026-11-09

### Added
- Initial release of Magento MCP Bridge extension for Adobe Commerce / Magento 2.
- WebAPI dispatch endpoint `/rest/default/V1/mcp/dispatch`.
