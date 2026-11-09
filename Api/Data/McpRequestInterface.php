<?php
declare(strict_types=1);

namespace PriyankJivani\McpBridge\Api\Data;

interface McpRequestInterface
{
    public const ACTION = 'action';
    public const PARAMS = 'params';
    public const DRY_RUN = 'dry_run';
    public const CONFIRM = 'confirm';

    /**
     * Get action name.
     *
     * @return string
     */
    public function getAction(): string;

    /**
     * Set action name.
     *
     * @param string $action
     * @return $this
     */
    public function setAction(string $action): self;

    /**
     * Get raw parameters string (JSON).
     *
     * @return string
     */
    public function getParams(): string;

    /**
     * Set raw parameters string (JSON).
     *
     * @param string $params
     * @return $this
     */
    public function setParams(string $params): self;

    /**
     * Get parsed parameters array.
     *
     * @return mixed[]
     */
    public function getParsedParams(): array;

    /**
     * Check if dry run.
     *
     * @return bool
     */
    public function isDryRun(): bool;

    /**
     * Set dry run.
     *
     * @param bool $dryRun
     * @return $this
     */
    public function setDryRun(bool $dryRun): self;

    /**
     * Check if confirmed.
     *
     * @return bool
     */
    public function isConfirm(): bool;

    /**
     * Set confirm.
     *
     * @param bool $confirm
     * @return $this
     */
    public function setConfirm(bool $confirm): self;
}

