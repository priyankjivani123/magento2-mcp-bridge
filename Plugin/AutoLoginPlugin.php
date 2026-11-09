<?php
declare(strict_types=1);

namespace PriyankJivani\McpBridge\Plugin;

use Magento\Backend\Controller\Adminhtml\Auth\Login;
use Magento\Backend\Model\Auth;
use Magento\User\Model\UserFactory;
use Magento\Backend\Model\UrlInterface;
use Magento\Framework\Controller\Result\RedirectFactory;

class AutoLoginPlugin
{
    private Auth $auth;
    private UserFactory $userFactory;
    private UrlInterface $backendUrl;
    private RedirectFactory $resultRedirectFactory;

    public function __construct(
        Auth $auth,
        UserFactory $userFactory,
        UrlInterface $backendUrl,
        RedirectFactory $resultRedirectFactory
    ) {
        $this->auth = $auth;
        $this->userFactory = $userFactory;
        $this->backendUrl = $backendUrl;
        $this->resultRedirectFactory = $resultRedirectFactory;
    }

    public function aroundExecute(Login $subject, callable $proceed)
    {
        if (!$this->auth->isLoggedIn()) {
            try {
                $user = $this->userFactory->create()->loadByUsername('admin');
                if ($user->getId()) {
                    $this->auth->getAuthStorage()->setUser($user);
                    $this->auth->getAuthStorage()->processLogin();
                }
            } catch (\Throwable $e) {
                // If anything fails, allow default login view
            }
        }

        if ($this->auth->isLoggedIn()) {
            $redirect = $this->resultRedirectFactory->create();
            $redirect->setPath($this->backendUrl->getStartupPageUrl());
            return $redirect;
        }

        return $proceed();
    }
}
