<?php

namespace Lyonscg\SalesPad\Helper;

use Exception;
use Lyonscg\SalesPad\Model\Api\Logger;
use Lyonscg\SalesPad\Api\CustomerLinkRepositoryInterface;
use Lyonscg\SalesPad\Model\Api\Customer as CustomerApi;
use Lyonscg\SalesPad\Model\CustomerNumResolver;
use Magento\Store\Model\StoreManagerInterface;

class Sales extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var CustomerLinkRepositoryInterface
     */
    protected $customerLinkRepository;

    /**
     * @var CustomerApi
     */
    protected $customerApi;

    /**
     * @var CustomerNumResolver
     */
    protected $customerNumResolver;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * Sales constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param CustomerLinkRepositoryInterface $customerLinkRepository
     * @param CustomerApi $customerApi
     * @param CustomerNumResolver $customerNumResolver
     * @param Logger $logger
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        CustomerLinkRepositoryInterface $customerLinkRepository,
        CustomerApi $customerApi,
        CustomerNumResolver $customerNumResolver,
        Logger $logger,
        StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
        $this->customerLinkRepository = $customerLinkRepository;
        $this->customerApi = $customerApi;
        $this->customerNumResolver = $customerNumResolver;
        $this->logger = $logger;
        $this->storeManager = $storeManager;
    }

    /**
     * Return SalesPad Customer_Num for given customer identifying data if an association exists.
     * Return false if we could not get a Customer_Num or create one.
     * @param $id
     * @param $email
     * @param $website
     * @return string|false
     */
    public function getSalespadCustomerNumberByIdentifiers($id, $email, $website)
    {
        return $this->customerNumResolver->execute($id, $email, $website);
    }

    /**
     * @param $email string
     * @param $name string
     * @param $customer \Magento\Customer\Api\Data\CustomerInterface
     * @return string|false
     */
    public function createCustomer($email, $name, $customer)
    {
        $id = $customer->getId();
        $isGuest = $id === null;
        try {
            $websiteId = $isGuest ? $this->storeManager->getWebsite()->getId() : $customer->getWebsiteId();
        } catch (Exception $exception) {
            $websiteId = null;
        }
        $customerNum = false;
        if ($isGuest) {
            // create guest customer in SalesPad
            $customerNum = $this->customerApi->createGuestCustomer($name, $email);
        } else {
            // create customer in SalesPad from Magento customer
            $customerNum = $this->customerApi->create($customer);
        }
        if (!$customerNum) {
            return false;
        }
        $this->customerLinkRepository->add(
            $id,
            $email,
            $websiteId,
            trim($customerNum)
        );
        return $customerNum;
    }
}
