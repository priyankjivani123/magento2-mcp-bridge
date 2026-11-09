<?php
declare(strict_types=1);

namespace PriyankJivani\McpBridge\Model\Action;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class ConfigHandler
{
    private ScopeConfigInterface $scopeConfig;

    /**
     * Patterns of configuration paths that MUST NEVER be exposed.
     */
    private array $sensitiveKeywords = [
        'password', 'secret', 'key', 'token', 'auth', 'private', 'crypt', 'salt', 'certificate'
    ];

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function getStoreConfig(array $params): array
    {
        $path = (string)($params['path'] ?? '');
        if ($path === '') {
            throw new \InvalidArgumentException('Config path is required.');
        }

        // Check for sensitive paths
        foreach ($this->sensitiveKeywords as $kw) {
            if (stripos($path, $kw) !== false) {
                return [
                    'path' => $path,
                    'value' => '******** (MASKED_SECURITY_PATH)',
                    'is_masked' => true
                ];
            }
        }

        $val = $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE);

        return [
            'path' => $path,
            'value' => $val,
            'is_masked' => false
        ];
    }
}
