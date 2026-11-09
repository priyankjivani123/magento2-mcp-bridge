# Magento 2 MCP Server - System Architecture & Protocol Design

**Architect:** Priyank Jivani – Senior Magento 2 / Adobe Commerce Architect  
**Specification:** Model Context Protocol (MCP) `2024-11-05`  
**Transport:** JSON-RPC 2.0 over Standard Input/Output (STDIO)

---

## 🏛️ System Overview

The Magento 2 Model Context Protocol integration bridges modern AI clients (such as Claude Desktop, Cursor AI, Windsurf, and custom autonomous LLM agents) with the Adobe Commerce / Magento 2 core platform.

```
+-------------------------------------------------------------------+
|                           AI CLIENT                               |
|        (Claude Desktop / Cursor AI / Autonomous Agent)            |
+---------------------------------+---------------------------------+
                                  |
               JSON-RPC 2.0 (STDIO) Protocol 2024-11-05
                                  |
                                  v
+-------------------------------------------------------------------+
|               MAGENTO 2 MCP SERVER (Standalone CLI)               |
|                      bin/magento-mcp-server                       |
|                                                                   |
|   +--------------------+  +------------------+  +-------------+   |
|   | StdioTransport     |  | ToolRegistry     |  | PromptReg   |   |
|   +--------------------+  +------------------+  +-------------+   |
|   | ServerConfig       |  | ResourceRegistry |  | ClientAPI   |   |
|   +--------------------+  +------------------+  +-------------+   |
+---------------------------------+---------------------------------+
                                  |
                    HTTPS / REST WebAPI (Bearer JWT)
                                  |
                                  v
+-------------------------------------------------------------------+
|               MAGENTO 2 MCP BRIDGE MODULE (Magento Core)          |
|                   vendor/priyankjivani/module-mcp-bridge          |
|                                                                   |
|   +-----------------------------------------------------------+   |
|   | /rest/default/V1/mcp/dispatch (McpDispatcherInterface)    |   |
|   +-----------------------------------------------------------+   |
|   | SecurityManager (READ_ONLY / WRITE / DESTRUCTIVE)         |   |
|   +-----------------------------------------------------------+   |
|   | Action Handlers:                                          |   |
|   | - ProductHandler       - OrderHandler       - CategoryHdl |   |
|   | - CustomerHandler      - InventoryHandler   - CmsHandler  |   |
|   | - DevToolsHandler      - PerformanceHandler - SeoHandler  |   |
|   +-----------------------------------------------------------+   |
|   | Plugins: Multi-Session Concurrency, Auto-Login             |   |
+---------------------------------+---------------------------------+
                                  |
           Internal Magento Service Contracts & Repositories
                                  |
                                  v
+-------------------------------------------------------------------+
|                     DATA & INFRASTRUCTURE LAYER                   |
|   MariaDB 11.8    |    Redis 8.0 Cache/Session    |   OpenSearch  |
+-------------------------------------------------------------------+
```

---

## 🔄 Execution Lifecycle

1. **AI Client Startup:** The AI client launches `bin/magento-mcp-server` as a child process with standard input and standard output pipes.
2. **Protocol Handshake:** The client sends an `initialize` JSON-RPC message. The server responds with protocol version `2024-11-05` and server capabilities.
3. **Dynamic Discovery:** The client queries `tools/list`, discovering 39 tools along with full JSON Schema definitions for typed parameter validation.
4. **Tool Invocation:** When the LLM decides to interact with Magento, it sends `tools/call`.
5. **Guardrail Check:** The MCP server inspects `MAGENTO_READ_ONLY`. If active and the tool is mutating, the server denies the call immediately.
6. **WebAPI Dispatch:** The server serializes the request and calls `/rest/default/V1/mcp/dispatch` with the configured Bearer JWT token.
7. **Magento Core Execution:** `McpDispatcher` verifies module security policies, maps the action to the corresponding handler, executes service contracts (e.g. `ProductRepositoryInterface`), and returns structured JSON.
8. **Client Response:** The MCP server formats the payload into standard MCP content blocks and outputs it to `stdout`.
