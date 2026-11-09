<?php
declare(strict_types=1);

namespace PriyankJivani\McpBridge\Model\Data;

use Magento\Framework\DataObject;
use PriyankJivani\McpBridge\Api\Data\McpRequestInterface;

class McpRequest extends DataObject implements McpRequestInterface
{
    public function getAction(): string
    {
        return (string)$this->getData(self::ACTION);
    }

    public function setAction(string $action): McpRequestInterface
    {
        return $this->setData(self::ACTION, $action);
    }

    public function getParams(): string
    {
        $params = $this->getData(self::PARAMS);
        return is_string($params) ? $params : (string)json_encode($params ?: []);
    }

    public function setParams(string $params): McpRequestInterface
    {
        return $this->setData(self::PARAMS, $params);
    }

    public function getParsedParams(): array
    {
        $params = $this->getData(self::PARAMS);
        if (is_array($params)) {
            return $params;
        }
        if (is_string($params)) {
            $decoded = json_decode($params, true);
            return is_array($decoded) ? $decoded : [];
        }
        return [];
    }

    public function isDryRun(): bool
    {
        return (bool)$this->getData(self::DRY_RUN);
    }

    public function setDryRun(bool $dryRun): McpRequestInterface
    {
        return $this->setData(self::DRY_RUN, $dryRun);
    }

    public function isConfirm(): bool
    {
        return (bool)$this->getData(self::CONFIRM);
    }

    public function setConfirm(bool $confirm): McpRequestInterface
    {
        return $this->setData(self::CONFIRM, $confirm);
    }
}
