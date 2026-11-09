<?php
declare(strict_types=1);

namespace PriyankJivani\McpBridge\Api\Data;

interface McpResponseInterface
{
    public const SUCCESS = 'success';
    public const ACTION = 'action';
    public const RESULT = 'result';
    public const ERROR = 'error';
    public const EXECUTION_TIME_MS = 'execution_time_ms';

    public function isSuccess(): bool;
    public function setSuccess(bool $success): self;

    public function getAction(): string;
    public function setAction(string $action): self;

    /**
     * @return mixed
     */
    public function getResult();

    /**
     * @param mixed $result
     */
    public function setResult($result): self;

    public function getError(): ?string;
    public function setError(?string $error): self;

    public function getExecutionTimeMs(): float;
    public function setExecutionTimeMs(float $ms): self;
}
