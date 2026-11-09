<?php
declare(strict_types=1);

namespace Magento\Framework\Component {
    if (!class_exists(ComponentRegistrar::class, false)) {
        class ComponentRegistrar
        {
            public const MODULE = 'module';
            public static function register(string $type, string $componentName, string $path): void {}
        }
    }
}

namespace Magento\Framework\Data {
    if (!interface_exists(OptionSourceInterface::class, false)) {
        interface OptionSourceInterface
        {
            public function toOptionArray(): array;
        }
    }
}

namespace Magento\Framework {
    if (!class_exists(DataObject::class, false)) {
        class DataObject
        {
            protected array $_data = [];
            public function __construct(array $data = []) { $this->_data = $data; }
            public function getData(?string $key = null) { return $key === null ? $this->_data : ($this->_data[$key] ?? null); }
            public function setData(string $key, $value = null) { $this->_data[$key] = $value; return $this; }
        }
    }
}

namespace Magento\Framework\Exception {
    if (!class_exists(LocalizedException::class, false)) {
        class LocalizedException extends \Exception {}
    }
    if (!class_exists(NoSuchEntityException::class, false)) {
        class NoSuchEntityException extends \Exception {}
    }
}

namespace Magento\Framework\App\Config {
    if (!interface_exists(ScopeConfigInterface::class, false)) {
        interface ScopeConfigInterface
        {
            public function getValue($path, $scopeType = 'default', $scopeCode = null);
            public function isSetFlag($path, $scopeType = 'default', $scopeCode = null): bool;
        }
    }
}

namespace Magento\Store\Model {
    if (!interface_exists(ScopeInterface::class, false)) {
        interface ScopeInterface
        {
            public const SCOPE_STORE = 'store';
            public const SCOPE_WEBSITE = 'website';
        }
    }
}

namespace {
    if (!function_exists('__')) {
        function __($text, ...$args) {
            return (string)$text;
        }
    }

    spl_autoload_register(function ($class) {
        $prefix = "PriyankJivani\\McpBridge\\";
        if (strpos($class, $prefix) === 0) {
            $relative = substr($class, strlen($prefix));
            $file = __DIR__ . '/../' . str_replace('\\', '/', $relative) . '.php';
            if (file_exists($file)) {
                require_once $file;
            }
        }
    });
}
