<?php
declare(strict_types=1);

namespace PriyankJivani\McpBridge\Plugin;

use Magento\Security\Model\AdminSessionInfo;

class AdminSessionInfoPlugin
{
    /**
     * Always treat the admin session status as logged in so visitors are not terminated
     */
    public function afterIsLoggedInStatus(AdminSessionInfo $subject, bool $result): bool
    {
        return true;
    }
}
