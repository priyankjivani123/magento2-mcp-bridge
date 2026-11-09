<?php
declare(strict_types=1);

namespace PriyankJivani\McpBridge\Model\Action;

class DevToolsHandler
{
    public function getVersion(): array
    {
        return [
            'magento_edition' => 'Adobe Commerce / Magento Open Source',
            'mcp_bridge_version' => '1.0.0',
            'php_version' => PHP_VERSION,
            'server_os' => PHP_OS,
            'protocol_version' => '2024-11-05'
        ];
    }

    public function getSystemHealth(): array
    {
        return [
            'php_memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'opcache_enabled' => function_exists('opcache_get_status') ? (bool)opcache_get_status(false) : false,
            'disk_free_gb' => round(disk_free_space('/') / 1024 / 1024 / 1024, 2),
            'status' => 'HEALTHY'
        ];
    }

    public function getPhpInformation(): array
    {
        return [
            'php_version' => PHP_VERSION,
            'loaded_extensions_count' => count(get_loaded_extensions()),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'default_timezone' => date_default_timezone_get()
        ];
    }
}
