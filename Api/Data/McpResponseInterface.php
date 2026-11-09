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

    /**
     * Check if success.
     *
     * @return bool
     */
    public function isSuccess(): bool;

    /**
     * Set success.
     *
     * @param bool $success
     * @return $this
     */
    public function setSuccess(bool $success): self;

    /**
     * Get action.
     *
     * @return string
     */
    public function getAction(): string;

    /**
     * Set action.
     *
     * @param string $action
     * @return $this
     */
    public function setAction(string $action): self;

    /**
     * Get result.
     *
     * @return string
     */
    public function getResult();

    /**
     * Set result.
     *
     * @param string $result
     * @return $this
     */
    public function setResult($result): self;

    /**
     * Get error.
     *
     * @return string|null
     */
    public function getError(): ?string;

    /**
     * Set error.
     *
     * @param string|null $error
     * @return $this
     */
    public function setError(?string $error): self;

    /**
     * Get execution time ms.
     *
     * @return float
     */
    public function getExecutionTimeMs(): float;

    /**
     * Set execution time ms.
     *
     * @param float $ms
     * @return $this
     */
    public function setExecutionTimeMs(float $ms): self;
}

