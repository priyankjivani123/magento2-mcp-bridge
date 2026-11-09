# Magento 2 MCP Server - Troubleshooting & Diagnostic Guide

**Author:** Priyank Jivani – Senior Magento 2 / Adobe Commerce Architect

---

## 🛠️ Common Issues & Solutions

### 1. "Magento Bridge Error (401): The consumer isn't authorized to access %resources."
- **Root Cause:** The Bearer JWT token is missing, expired, or generated for a user without WebAPI permissions.
- **Solution:** Verify your token with `magento_get_version`. In the live environment, token lifetime has been configured to 10 years (`webapi/jwtauth/admin_expiration = 5256000`). Use the dedicated token provided in the README.

### 2. "Tool 'X' is blocked: MCP Server is operating in READ_ONLY mode."
- **Root Cause:** Mutating tools (`WRITE` or `DESTRUCTIVE`) cannot be executed when `MAGENTO_READ_ONLY=true`.
- **Solution:** Set `MAGENTO_READ_ONLY=false` in your client environment configuration.

### 3. "Action magento_delete_product is a DESTRUCTIVE operation. Explicit confirmation is required."
- **Root Cause:** Safeguard triggered because `confirm` was omitted or `false`.
- **Solution:** Pass `"confirm": true` in the tool call arguments when deletion is intended.

### 4. "cURL error 7: Failed to connect to 152.67.31.72 port 443"
- **Root Cause:** In cloud environments (such as Oracle Cloud Infrastructure), port 443 ingress may be restricted in the VCN Security List while Port 80 is open.
- **Solution:** Use `http://152.67.31.72` as `MAGENTO_BASE_URL`.

### 5. "Deprecated Functionality: Function curl_close() is deprecated since 8.5"
- **Root Cause:** PHP 8.5 emits a deprecation notice for `curl_close()`.
- **Solution:** `PriyankJivani_McpServer` and `PriyankJivani_McpBridge` have been updated for complete PHP 8.5 compatibility.
