<?php
declare(strict_types=1);

namespace PriyankJivani\McpBridge\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class OperationMode implements OptionSourceInterface
{
    public const MODE_READ_ONLY = 'read_only';
    public const MODE_READ_WRITE = 'read_write';

    public function toOptionArray(): array
    {
        return [
            ['value' => self::MODE_READ_ONLY, 'label' => __('Read-Only (Non-Destructive)')],
            ['value' => self::MODE_READ_WRITE, 'label' => __('Read / Write (Full Access with Safeguards)')]
        ];
    }
}
