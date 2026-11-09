<?php
declare(strict_types=1);

namespace PriyankJivani\McpBridge\Model\Data;

use Magento\Framework\DataObject;
use PriyankJivani\McpBridge\Api\Data\McpResponseInterface;

class McpResponse extends DataObject implements McpResponseInterface
{
    public function isSuccess(): bool
    {
        return (bool)$this->getData(self::SUCCESS);
    }

    public function setSuccess(bool $success): McpResponseInterface
    {
        return $this->setData(self::SUCCESS, $success);
    }

    public function getAction(): string
    {
        return (string)$this->getData(self::ACTION);
    }

    public function setAction(string $action): McpResponseInterface
    {
        return $this->setData(self::ACTION, $action);
    }

    public function getResult()
    {
        return $this->getData(self::RESULT);
    }

    public function setResult($result): McpResponseInterface
    {
        return $this->setData(self::RESULT, $result);
    }

    public function getError(): ?string
    {
        return $this->getData(self::ERROR) !== null ? (string)$this->getData(self::ERROR) : null;
    }

    public function setError(?string $error): McpResponseInterface
    {
        return $this->setData(self::ERROR, $error);
    }

    public function getExecutionTimeMs(): float
    {
        return (float)$this->getData(self::EXECUTION_TIME_MS);
    }

    public function setExecutionTimeMs(float $ms): McpResponseInterface
    {
        return $this->setData(self::EXECUTION_TIME_MS, $ms);
    }
}
