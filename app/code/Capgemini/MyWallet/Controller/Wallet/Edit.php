<?php
/**
 * Capgemini_MyWallet
 *
 * @category   Capgemini
 * @author    Yaroslav Protsko <yaroslav.protsko@capgemini.com>
 * @copyright 2020 Capgemini, Inc (www.capgemini.com)
 */

namespace Capgemini\MyWallet\Controller\Wallet;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\UrlInterface;

class Edit extends \Magento\Framework\App\Action\Action
{
    /**
    * @var  \Magento\Framework\View\Result\Page
    */
    protected $resultPageFactory;

    /**
     * @var UrlInterface
     */
    protected $urlInterface;

    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * Index constructor
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param Session $customerSession
     * @param UrlInterface $urlInterface
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Session $customerSession,
        UrlInterface $urlInterface
    ) {
        $this->urlInterface = $urlInterface;
        $this->customerSession = $customerSession;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * If user is logged in
     *
     * @return bool
     */
    public function isLoggedIn()
    {
        return $this->customerSession->isLoggedIn();
    }

    /**
     * @return PageFactory|\Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        if ($this->isLoggedIn()) {
            $walletId = $this->getRequest()->getParam('id');
            $resultPage = $this->resultPageFactory->create();
            $resultPage->getConfig()->getTitle()->set(__('Edit Card'));
            $block = $resultPage->getLayout()->getBlock('my_wallet_wallet_edit');
            if ($block) {
                $block->setCurrentWallet('test ' . $walletId );
            }
            return $resultPage;
        } else {
            $url = $this->_redirect->getRefererUrl();
            $loginUrl = $this->urlInterface->getUrl('customer/account/login', array('referer' => base64_encode($url)));

            /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setUrl($loginUrl);
            return $resultRedirect;
        }
    }
}
