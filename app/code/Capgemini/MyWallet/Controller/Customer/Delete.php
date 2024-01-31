<?php
/**
 * Capgemini_MyWallet
 *
 * @category   Capgemini
 * @author    Yaroslav Protsko <yaroslav.protsko@capgemini.com>
 * @copyright 2020 Capgemini, Inc (www.capgemini.com)
 */

namespace Capgemini\MyWallet\Controller\Customer;

use Capgemini\MyWallet\Model\WalletApi;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\UrlInterface;
use Capgemini\MyWallet\Helper\Wallet;
use Capgemini\MyWallet\Api\Data\WalletInterface;
use Capgemini\MyWallet\Api\WalletRepositoryInterface;
use Capgemini\Payfabric\Logger\Logger;

class Delete extends \Magento\Framework\App\Action\Action
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
     * @var Wallet
     */
    protected $myWalletHelper;

    /**
     * @var WalletApi
     */
    protected $walletApi;


    /**
     * @var WalletInterface
     */
    protected $wallet;

    /**
     * @var WalletRepositoryInterface
     */
    protected $walletRepository;

    /**
     * @var \Capgemini\Payfabric\Logger\Logger
     */
    protected $capgeminiLogger;

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
        UrlInterface $urlInterface,
        Wallet $myWalletHelper,
        WalletApi $walletApi,
        WalletInterface $wallet,
        WalletRepositoryInterface $walletRepository,
        Logger $capgeminiLogger

    ) {
        $this->myWalletHelper = $myWalletHelper;
        $this->walletApi = $walletApi;
        $this->wallet = $wallet;
        $this->walletRepository = $walletRepository;
        $this->capgeminiLogger = $capgeminiLogger;
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
            $wallet = $this->myWalletHelper->loadWalletById($walletId);
            if ($wallet->getWalletId()) {
                $walletApiResponse = $this->walletApi->deleteWallet($wallet->getPayfabricWalletId());

                if (isset($walletApiResponse['Result']) && !empty($walletApiResponse['Result'])) {
                    $this->messageManager->addSuccessMessage(__("The credit card has been removed successfully"));
                    $this->walletRepository->deleteById($wallet->getCustomerId(), $walletId);
                } else {
                    $this->capgeminiLogger->error('Error during removing card. Customer id ' . $wallet->getCustomerId()
                        . " Payfabric response " . print_r($walletApiResponse, true));
                    $this->messageManager->addErrorMessage(__("Something went wrong during removing card. Please ask website administrator"));
                }
            } else {
                $this->messageManager->addErrorMessage(__("Something went wrong during removing card. Please ask website administrator"));
            }
            $walletListUrl = $this->urlInterface->getUrl('mywallet/customer/index');
            /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setUrl($walletListUrl);

            return $resultRedirect;
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
