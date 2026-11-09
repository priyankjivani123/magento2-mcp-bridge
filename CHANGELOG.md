# Changelog - Magento 2 MCP Bridge

All notable changes to this project will be documented in this file.

## [1.0.0] - 2026-11-09
### Added
- Initial enterprise open-source release of `PriyankJivani_McpBridge`.
- Full WebAPI REST dispatcher endpoint (`/V1/mcp/dispatch`).
- Health ping endpoint (`/V1/mcp/ping`).
- Security classification engine (`READ_ONLY`, `WRITE`, `DESTRUCTIVE`).
- Dry-run preview engine for write and bulk operations.
- Explicit confirmation safeguard for destructive actions.
- Automatic masking for sensitive configuration paths (passwords, secrets, private keys).
- PII sanitization for customer queries.
- Action handlers for Products, Orders, Customers, Inventory, Categories, CMS, Store Config, Developer Diagnostics, Performance Auditing, and SEO.
- Automated PHPUnit unit test suite.
- Strict PHP 8.1 - 8.4 compatibility.
