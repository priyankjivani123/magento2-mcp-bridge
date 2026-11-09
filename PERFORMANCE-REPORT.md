# Magento 2 MCP Server - Performance & Benchmark Report

**Performance Engineer:** Priyank Jivani – Senior Magento 2 / Adobe Commerce Architect  
**Benchmarking Environment:** Oracle Cloud Infrastructure (aarch64)  
**Magento Version:** 2.4.9 Open Source / Adobe Commerce  
**PHP Runtime:** PHP 8.5.4 FPM + CLI with OPcache & JIT Enabled  
**Database:** MariaDB 11.8.6  
**Search:** OpenSearch 2.18  
**Cache Backend:** Redis 8.0.5  
**Date:** 2026-11-09

---

## 🚀 Benchmark Summary: 50 Consecutive Requests

50 consecutive tool requests (`magento_get_version`) dispatched over STDIO transport through the MCP server to Magento 2.4.9 REST WebAPI:

| Metric | Measured Value | Standard Target | Status |
|---|---|---|---|
| **Total Requests** | 50 | 50 | Completed |
| **Failed Requests** | 0 | 0 | **100% Reliability** |
| **Min Latency** | 226.26ms | < 300ms | **OPTIMAL** |
| **Max Latency** | 248.37ms | < 400ms | **OPTIMAL** |
| **Average Latency (Mean)** | **238.06ms** | < 300ms | **OPTIMAL** |
| **50th Percentile (p50)** | **237.80ms** | < 300ms | **OPTIMAL** |
| **95th Percentile (p95)** | **242.43ms** | < 350ms | **OPTIMAL** |
| **99th Percentile (p99)** | **247.90ms** | < 400ms | **OPTIMAL** |

---

## ⚡ Latency Breakdown by Layer

1. **STDIO Transport & JSON-RPC 2.0 Serialization:** < 0.15ms
2. **Local HTTP/CURL Transport:** ~ 0.8ms
3. **Magento 2.4.9 Framework Bootstrap & Routing:** ~ 180ms
4. **ACL Authentication & JWT Token Verification:** ~ 35ms
5. **Bridge Dispatcher & Service Contract Execution:** ~ 15ms
6. **Total Roundtrip Latency:** **~ 238ms**

---

## 💡 Performance Optimization Recommendations

1. **Enable Redis Backend & Session Cache:** Ensure Redis is active on `127.0.0.1:6379` for both default cache and page cache.
2. **Enable OPcache with Comments Preservation:** In `php.ini`, set `opcache.enable=1`, `opcache.save_comments=1`, and `opcache.jit=tracing`.
3. **Compile DI Configuration:** In production mode, always run `bin/magento setup:di:compile` to generate optimized interceptors and repositories.
