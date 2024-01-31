<?php
/**
 * @category     Capgemini
 * @package      Capgemini_DataLayer
 */

namespace Capgemini\DataLayer\Block;

use Magento\Framework\View\Element\Template;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Theme\Block\Html\Header\Logo;
use Magento\Company\Api\CompanyManagementInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Helper\Data;

class Events extends Template
{
    /**
     * @var \Magento\Framework\Serialize\SerializerInterface
     */
    protected $serializer;

    /**
     * @var \Capgemini\DataLayer\Helper\Data
     */
    protected $helper;

    public function __construct(
        Template\Context $context,
        \Magento\Framework\Serialize\SerializerInterface $serializer,
        \Capgemini\DataLayer\Helper\Data $helper,
        array $data = [])
    {
        $this->serializer = $serializer;
        $this->helper = $helper;
        parent::__construct($context, $data);
    }

    public function getEmailSubscriptionData()
    {
        $customerData = $this->helper->getCustomerData();

        $pageData = [];
        $pageData['userEmail'] = $customerData['userEmail'];
        $pageData['hashedEmail'] = $customerData['hashedEmail'];
        $pageData['submitLocation'] = 'footer';

        return $this->serializer->serialize($pageData);
    }
}
