<?php
declare(strict_types=1);

namespace PriyankJivani\McpBridge\Plugin;

use Magento\Security\Model\AdminSessionsManager;

class AdminSessionManagerPlugin
{
    /**
     * Prevent terminating other admin sessions so multiple visitors can test the admin simultaneously
     */
    public function aroundLogoutOtherUserSessions(AdminSessionsManager $subject, callable $proceed)
    {
        return $subject;
    }

    /**
     * Prevent returning termination reason
     */
    public function aroundGetLogoutReasonMessage(AdminSessionsManager $subject, callable $proceed)
    {
        return null;
    }

    public function aroundGetLogoutReasonMessageByStatus(AdminSessionsManager $subject, callable $proceed, $statusCode)
    {
        return null;
    }
}
