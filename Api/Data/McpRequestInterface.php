<?php
declare(strict_types=1);

namespace PriyankJivani\McpBridge\Api\Data;

interface McpRequestInterface
{
    public const ACTION = 'action';
    public const PARAMS = 'params';
    public const DRY_RUN = 'dry_run';
    public const CONFIRM = 'confirm';

    public function getAction(): string;
    public function setAction(string $action): self;

    /**
     * @return mixed[]
     */
    public function getParams(): array;

    /**
     * @param mixed[] $params
     */
    public function setParams(array $params): self;

    public function isDryRun(): bool;
    public function setDryRun(bool $dryRun): self;

    public function isConfirm(): bool;
    public function setConfirm(bool $confirm): self;
}
