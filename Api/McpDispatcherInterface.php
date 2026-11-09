<?php
declare(strict_types=1);

namespace PriyankJivani\McpBridge\Api;

use PriyankJivani\McpBridge\Api\Data\McpRequestInterface;
use PriyankJivani\McpBridge\Api\Data\McpResponseInterface;

interface McpDispatcherInterface
{
    /**
     * Dispatch an authorized MCP tool call.
     *
     * @param \PriyankJivani\McpBridge\Api\Data\McpRequestInterface $request
     * @return \PriyankJivani\McpBridge\Api\Data\McpResponseInterface
     */
    public function dispatch(McpRequestInterface $request): McpResponseInterface;

    /**
     * Basic ping/health check.
     *
     * @return mixed[]
     */
    public function ping(): array;
}
