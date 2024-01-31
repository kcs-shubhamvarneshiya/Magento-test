<?php
/**
 * Capgemini_MyWallet
 *
 * @category   Capgemini
 * @author    Yaroslav Protsko <yaroslav.protsko@capgemini.com>
 * @copyright 2020 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\MyWallet\Block\Customer;

class Index extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Capgemini\MyWallet\Model\ResourceModel\Wallet\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Capgemini\MyWallet\Helper\Wallet
     */
    protected $myWalletHelper;

    /**
     * @var \Magento\Framework\Serialize\SerializerInterface
     */
    protected $serializer;

    /**
     * Core store config
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Index constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Capgemini\MyWallet\Model\ResourceModel\Wallet\CollectionFactory $collectionFactory
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Capgemini\MyWallet\Helper\Wallet $myWalletHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Capgemini\MyWallet\Model\ResourceModel\Wallet\CollectionFactory $collectionFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Capgemini\MyWallet\Helper\Wallet $myWalletHelper,
        \Magento\Framework\Serialize\SerializerInterface $serializer,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        array $data = []
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->collectionFactory  = $collectionFactory;
        $this->customerSession = $customerSession;
        $this->myWalletHelper = $myWalletHelper;
        $this->serializer = $serializer;

        parent::__construct($context, $data);
    }

    /**
     * @return array
     */
    public function getCustomerWallets()
    {
        return $this->myWalletHelper->getCustomerWallets();
    }

    /**
     * @return string
     */
    public function getEditWalletUrl()
    {
        return $this->myWalletHelper->getEditWalletUrl();
    }

    /**
     * @return string
     */
    public function getDeleteWalletUrl()
    {
        return $this->myWalletHelper->getDeleteWalletUrl();
    }

    /**
     * @return string
     */
    public function getAddWalletUrl()
    {
        return $this->myWalletHelper->getAddWalletUrl();
    }

    /**
     * @return array
     */
    public function getMonthsList()
    {
        return [
            '01 - January',
            '02 - February',
            '03 - March',
            '04 - April',
            '05 - May',
            '06 - June',
            '07 - July',
            '08 - August',
            '09 - September',
            '10 - October',
            '11 - November',
            '12 - December',
        ];
    }

    /**
     * @return array
     */
    public function getYearsList()
    {
        $currentYear = date('Y');
        $years[] = (int)$currentYear;
        for ($i=1; $i <= 10; $i++) {
            $years[] = $currentYear  + $i;
        }

        return $years;
    }

    /**
     * @return array
     */
    public function getCcTypes()
    {
        $path = 'payment/payfabric/cctypes';
        $ccTypesFromDb = $this->scopeConfig->getValue($path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $ccTypesFromDb = explode(',', $ccTypesFromDb);
        $ccTypes = $this->myWalletHelper->getCcTypes();
        foreach ($ccTypes as $type => $value) {
            if(!(in_array($value, $ccTypesFromDb))) {
                unset($ccTypes[$type]);
            }
        }

        return $ccTypes;
    }

    /**
     * @return array
     */
    public function getBillingAddresses()
    {
        return $this->myWalletHelper->getCustomerBillingAddresses();
    }
}
