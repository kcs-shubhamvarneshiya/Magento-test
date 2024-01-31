<?php
namespace Capgemini\DataLayer\Plugin;

use Magento\Catalog\Controller\Adminhtml\Product\Initialization\Helper;
use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Customer\Helper\View;
use Capgemini\DataLayer\Helper\Data as DataLayerHelper;

/**
 * Customer section
 */
class CustomerDataLayer
{
    /**
     * @var CurrentCustomer
     */
    protected $currentCustomer;

    /**
     * @var View
     */
    private $customerViewHelper;

    protected $dataLayerHelper;

    /**
     * @param CurrentCustomer $currentCustomer
     * @param View $customerViewHelper
     */
    public function __construct(
        CurrentCustomer $currentCustomer,
        View $customerViewHelper,
        DataLayerHelper $dataLayerHelper
    ) {
        $this->currentCustomer = $currentCustomer;
        $this->customerViewHelper = $customerViewHelper;
        $this->dataLayerHelper = $dataLayerHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function afterGetSectionData($subject, $result)
    {
        $customerData = $this->dataLayerHelper->getCustomerData();

        $result['userEmail'] = $customerData['userEmail'];
        $result['hashedEmail'] = $customerData['hashedEmail'];
        $result['loggedinStatus'] = $customerData['loggedinStatus'];
        $result['currencyCode'] = $customerData['currencyCode'];
        $result['tradeCustomer'] = $customerData['tradeCustomer'];
        $result['customerClass'] = $customerData['customerClass'];

        return $result;
    }
}
