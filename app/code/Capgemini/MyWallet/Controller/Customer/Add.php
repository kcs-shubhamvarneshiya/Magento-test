<?php
/**
 * Capgemini_MyWallet
 *
 * @category   Capgemini
 * @author    Yaroslav Protsko <yaroslav.protsko@capgemini.com>
 * @copyright 2020 Capgemini, Inc (www.capgemini.com)
 */

namespace Capgemini\MyWallet\Controller\Customer;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\UrlInterface;
use Capgemini\MyWallet\Helper\Wallet;
use Capgemini\MyWallet\Api\Data\WalletInterface;
use Capgemini\MyWallet\Api\WalletRepositoryInterface;
use Capgemini\MyWallet\Model\WalletApi;
use Capgemini\Payfabric\Helper\Data as payfabricHelper;
use Capgemini\Payfabric\Logger\Logger;
use Magento\Framework\Json\Helper\Data as jsonHelper;
use Magento\Framework\Controller\Result\JsonFactory;

class Add extends \Magento\Framework\App\Action\Action
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
     * @var payfabricHelper
     */
    protected $payfabricHelper;

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
     * @var jsonHelper
     */
    protected $jsonHelper;

    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

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
        payfabricHelper $payfabricHelper,
        WalletInterface $wallet,
        WalletRepositoryInterface $walletRepository,
        Logger $capgeminiLogger,
        jsonHelper $jsonHelper,
        jsonFactory $resultJsonFactory
    ) {
        $this->wallet = $wallet;
        $this->walletRepository = $walletRepository;
        $this->myWalletHelper = $myWalletHelper;
        $this->payfabricHelper = $payfabricHelper;
        $this->urlInterface = $urlInterface;
        $this->customerSession = $customerSession;
        $this->resultPageFactory = $resultPageFactory;
        $this->walletApi = $walletApi;
        $this->capgeminiLogger = $capgeminiLogger;
        $this->jsonHelper = $jsonHelper;
        $this->resultJsonFactory = $resultJsonFactory;
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
            if ($this->getRequest()->isAjax()) {
                $ccData = $this->jsonHelper->jsonDecode($this->getRequest()->getContent());
                //remove non digit characters.
                $ccData['cc_number'] = preg_replace('/\\D/', '', $ccData['cc_number']);
                $customerId = $this->myWalletHelper->getCustomerId();

                /** @var \Capgemini\MyWallet\Model\ResourceModel\Wallet\Collection $walletCollection */
                $walletCollection = $this->walletRepository->search($customerId);

                $customerCards = $this->walletApi->retrieveCardsByCustomerId($customerId);

                $cardNum = $this->payfabricHelper->ccMasking($ccData['cc_number'], 'X');
                $cardExpDate = $this->payfabricHelper->getCardExpDate($ccData);
                $cardType = $this->payfabricHelper->getFullCardName($ccData['cc_type']);
                if(!is_array($customerCards)) {
                    $isWalletExist['status'] = false;
                } else {
                    $isWalletExist = $this->payfabricHelper->isCustomerWalletExist($customerCards, $cardNum, $cardType, $cardExpDate);
                }
                if ($isWalletExist['status'] === true) {
                    // we need to check if card/wallet exists on Magento and create if needed
                    $isWalletExistOnMagento = false;
                    foreach ($walletCollection as $wallet) {
                        if ($wallet->getPayfabricWalletId() == $isWalletExist['status']['ID']){
                            $isWalletExistOnMagento = true;
                            break;
                        }
                    }
                    if ($isWalletExistOnMagento === true) {
                        if ($this->getRequest()->isAjax()) {
                            $result = $this->resultJsonFactory->create();
                            $response = Array('error_message' => __("This card already exists. Please add another one"));

                            return $result->setData($response);
                        } else {
                            $this->messageManager->addErrorMessage(__("This card already exists. Please add another one"));
                        }
                    } else {
                        // we have card on payfabric BUT not on Magento
                        $data = array(
                            'customer_id'  => $customerId,
                            'cc_nickname' => $ccData['cc_nickname'],
                            'cc_last4'      => $cardNum,
                            'card_name'     => $cardType,
                            'card_exp_date' => $cardExpDate,
                            'created_at'    => date('Y-m-d H:i:s'),
                            'payfabric_wallet_id' => $isWalletExist['status']['ID'],
                            'cc_holder_name' => $ccData['cc_holder_name']
                        );
                        if (isset($ccData['is_default']) && $ccData['is_default']) {
                            $data['is_default'] = true;
                        }
                        if (isset($ccData['cc_nickname']) && $ccData['cc_nickname']) {
                            $data['cc_nickname'] = $ccData['cc_nickname'];
                        }
                        $this->wallet->setData($data);
                        $this->walletRepository->save($customerId, $this->wallet);

                        $this->messageManager->addSuccessMessage(__("The credit card has been created successfully"));
                        $walletListUrl = $this->urlInterface->getUrl('mywallet/customer/index');
                        if ($this->getRequest()->isAjax()) {
                            $result = $this->resultJsonFactory->create();
                            $response = array('success' => true,
                                'wallet_list_url' => $walletListUrl);

                            return $result->setData($response);
                        }
                        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                        $resultRedirect = $this->resultRedirectFactory->create();
                        $resultRedirect->setUrl($walletListUrl);

                        return $resultRedirect;
                    }
                } else {
                    $walletApiResponse = $this->walletApi->createWallet($ccData);
                    if (isset($walletApiResponse['Result']) && !empty($walletApiResponse['Result'])) {
                        $data = array(
                            'customer_id'  => $customerId,
                            'cc_nickname' => $ccData['cc_nickname'],
                            'cc_last4'      => $cardNum,
                            'card_name'     => $cardType,
                            'card_exp_date' => $cardExpDate,
                            'created_at'    => date('Y-m-d H:i:s'),
                            'payfabric_wallet_id' => $walletApiResponse['Result'],
                            'cc_holder_name' => $ccData['cc_holder_name']
                        );
                        if (isset($ccData['is_default']) && $ccData['is_default']) {
                            $data['is_default'] = true;
                        }
                        if (isset($ccData['cc_nickname']) && $ccData['cc_nickname']) {
                            $data['cc_nickname'] = $ccData['cc_nickname'];
                        }
                        $this->wallet->setData($data);
                        $this->walletRepository->save($customerId, $this->wallet);

                        $this->messageManager->addSuccessMessage(__("The credit card has been created successfully"));
                        $walletListUrl = $this->urlInterface->getUrl('mywallet/customer/index');
                        if ($this->getRequest()->isAjax()) {
                            $result = $this->resultJsonFactory->create();
                            $response = array('success' => true,
                                              'wallet_list_url' => $walletListUrl);

                            return $result->setData($response);
                        }
                        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                        $resultRedirect = $this->resultRedirectFactory->create();
                        $resultRedirect->setUrl($walletListUrl);

                        return $resultRedirect;
                    } else {
                        $this->capgeminiLogger->error('Error during creating card. Customer id ' . $customerId
                            . " Payfabric response " . print_r($walletApiResponse, true));
                        if ($this->getRequest()->isAjax()) {
                            $result = $this->resultJsonFactory->create();
                            $response = array('error_message' => __("Something went wrong during create card. Please ask website administrator"));

                            return $result->setData($response);
                        } else {
                            $this->messageManager->addErrorMessage(__("Something went wrong during create card. Please ask website administrator"));
                        }
                    }
                }
            }
            $resultPage = $this->resultPageFactory->create();
            $resultPage->getConfig()->getTitle()->set(__('Add Credit Card'));

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
