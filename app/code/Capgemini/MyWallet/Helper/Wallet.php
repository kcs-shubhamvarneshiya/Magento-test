<?php
/**
 * Capgemini_MyWallet
 *
 * @category  Lyonscg
 * @package   Lyonscg_MyGarage
 * @copyright Copyright (c) 2018 Lyons Consulting Group (www.lyonscg.com)
 */
namespace Capgemini\MyWallet\Helper;

use Magento\Store\Model\ScopeInterface;

/**
 * Class Vehicle
 * @package Lyonscg\MyGarage\Helper
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Wallet extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Capgemini\MyWallet\Model\Wallet
     */
    protected $wallet;

    /**
     * @var \Magento\Framework\Serialize\SerializerInterface
     */
    protected $serializer;

    /**
     * @var \Magento\Customer\Model\SessionFactory
     */
    protected $sessionFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $httpContext;

    /**
     * @var \Capgemini\MyWallet\Model\ResourceModel\Wallet
     */
    protected $walletResource;

    /**
     * @var \Magento\Framework\App\CacheInterface
     */
    protected $cache;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlInterface;

    /**
     * @var \Capgemini\MyWallet\Api\WalletRepositoryInterface
     */
    protected $walletRepository;

    /**
     * @var \Lyonscg\SalesPad\Model\CustomerNumResolver
     */
    protected $customerNumResolver;

    /**
     * @var array
     */
    protected $ccTypes = array('AmericanExpress' => 'AE',
                                'Visa' => 'VI',
                                'MasterCard' => 'MC',
                                'Discover' => 'DI');

    /**
     * @var \Magento\Customer\Model\AddressFactory
     */
    protected $addressFactory;

    /**
     * Wallet constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Capgemini\MyWallet\Model\Wallet $wallet
     * @param \Capgemini\MyWallet\Model\ResourceModel\Wallet $walletResource
     * @param \Magento\Framework\Serialize\SerializerInterface $serializer
     * @param \Magento\Customer\Model\SessionFactory $sessionFactory
     * @param \Magento\Framework\App\CacheInterface $cache
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\UrlInterface $urlInterface
     * @param \Capgemini\MyWallet\Api\WalletRepositoryInterface $walletRepository
     * @param \Magento\Customer\Model\AddressFactory $addressFactory
     * @param \Lyonscg\SalesPad\Model\CustomerNumResolver $customerNumResolver
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Capgemini\MyWallet\Model\Wallet $wallet,
        \Capgemini\MyWallet\Model\ResourceModel\Wallet $walletResource,
        \Magento\Framework\Serialize\SerializerInterface $serializer,
        \Magento\Customer\Model\SessionFactory $sessionFactory,
        \Magento\Framework\App\CacheInterface $cache,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\UrlInterface $urlInterface,
        \Capgemini\MyWallet\Api\WalletRepositoryInterface $walletRepository,
        \Magento\Customer\Model\AddressFactory $addressFactory,
        \Lyonscg\SalesPad\Model\CustomerNumResolver $customerNumResolver
    ) {
        $this->walletRepository = $walletRepository;
        $this->vehicle = $wallet;
        $this->serializer = $serializer;
        $this->httpContext = $httpContext;
        $this->walletResource = $walletResource;
        $this->sessionFactory = $sessionFactory;
        $this->cache = $cache;
        $this->storeManager = $storeManager;
        $this->urlInterface = $urlInterface;
        $this->addressFactory = $addressFactory;
        $this->customerNumResolver = $customerNumResolver;
        parent::__construct($context);
    }

    /**
     * @return int|mixed|null
     */
    public function isLoggedIn()
    {
        $isLoggedIn = $this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);

        if ($isLoggedIn) {
            return $isLoggedIn;
        } else {
            return 0;
        }
    }

    /**
     * @return int|mixed
     */
    public function getCustomerId()
    {
        $customerSession = $this->sessionFactory->create();

        if ($customerSession->isLoggedIn()) {
            try {
                $customerData = $customerSession->getCustomerData();
                // CRC-638 Moved sales_pad_customer_num customer attribute from a custom to an extension one.
                if ($salesPadCustomerNum = $customerData->getExtensionAttributes()->getSalesPadCustomerNum()) {

                    return $salesPadCustomerNum;
                }
            } catch (\Exception $e) {
                $customer = $customerSession->getCustomer();
                $customerId = $this->getCustomerIdFromIdentifiers(
                    $customer->getId(),
                    $customer->getEmail(),
                    $customer->getWebsiteId()
                );
                return $customerId ? $customerId : 0;
            }
        } else {
            return 0;
        }
    }

    public function getCustomerIdFromIdentifiers($id, $email, $website)
    {
        return $this->customerNumResolver->execute($id, $email, $website);
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCustomerWallets()
    {
        $customerId = $this->getCustomerId();

       $customerWallets = $this->walletResource->getCustomerWallets($customerId);
       if (count($customerWallets) > 0) {
           foreach ($customerWallets as $key =>$wallet) {
               $expDate = $wallet['card_exp_date'];
               $wallet['card_exp_date']= substr($expDate,0,2) . '/' . '20'. substr($expDate,2,2);
               $wallet['cc_last4']= str_replace('X', '*',  $wallet['cc_last4']);
               $customerWallets[$key] = $wallet;
           }
           return $customerWallets;
       } else {
           return array();
       }
    }

    public function getDeleteWalletUrl()
    {
        return $this->urlInterface->getUrl('mywallet/customer/delete');
    }

    /**
     * @return string
     */
    public function getAddWalletUrl()
    {
        return $this->urlInterface->getUrl('mywallet/customer/add');
    }

    /**
     * @return string
     */
    public function getUpdateWalletUrl()
    {
        return $this->urlInterface->getUrl('mywallet/customer/update');
    }

    /**
     * @param $walletId
     * @return \Capgemini\MyWallet\Api\Data\WalletInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function loadWalletById($walletId)
    {
        $customerId = $this->getCustomerId();

        $wallet = $this->walletRepository->get($customerId,$walletId);
        $expDate = $wallet['card_exp_date'];
        $wallet->setData('exp_month', (int)substr($expDate,0,2));
        $wallet->setData('exp_year', (int)'20'. substr($expDate,2,2));
        $wallet->setData('card_type', $this->getShortCardType($wallet['card_name']));
        return $wallet;
    }

    /**
     * Get Full Credit Card Name
     *
     * @param Varien_Object $payment
     * @return string
     */
    public function getShortCardType($cardName)
    {
        If (isset($this->ccTypes[$cardName])) {
            return $this->ccTypes[$cardName];
        } else {
            return 'Other';
        }
    }

    /**
     * @return array
     */
    public function getCcTypes()
    {
        return $this->ccTypes;
    }

    /**
     * @return array
     */
    public function getCustomerBillingAddresses()
    {
        $customer = $this->getCustomer();
        $customerAddresses = $customer->getAddresses();
        $defaultBillingAddress = $customer->getDefaultBillingAddress();
        $defaultBillingAddressId = $defaultBillingAddress ? $defaultBillingAddress->getId() : null;
        $customerBilllingAddresses = [];
        foreach ($customerAddresses as $address) {
            if (count($customerAddresses) > 1 && $defaultBillingAddressId && $address->getId() == $defaultBillingAddressId) {
                continue; 
            }
            $customerBillingAddresses[] = $address;
        }
        if ($defaultBillingAddress) {
            $customerBillingAddresses[] = $defaultBillingAddress;
        }
    
        return $customerBillingAddresses;
    }

    /**
     * @return \Magento\Customer\Model\Customer
     */
    public function getCustomer()
    {
        $customerSession = $this->sessionFactory->create();

        return $customerSession->getCustomer();
    }

    /**
     * @param $addressId
     * @return \Magento\Customer\Model\Address
     */
    public  function getCustomerAddressById($addressId)
    {
        return $this->addressFactory->create()->load($addressId);
    }
}
