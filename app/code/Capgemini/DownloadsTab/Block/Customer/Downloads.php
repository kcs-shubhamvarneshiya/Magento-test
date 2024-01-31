<?php
namespace Capgemini\DownloadsTab\Block\Customer;

use Magento\Framework\View\Element\Template;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Customer\Model\Session;
use Capgemini\OrderView\Helper\Data;

class Downloads extends Template
{
    protected $_mediaDirectory;
    protected $_fileList;
    protected $customerSession;
     /**
     * @param Data $helper
     * @var string
     */
    protected $customerType;
    /**
     * @var Data
     */
    protected $helper;

    public function __construct(
        Template\Context $context,
        DirectoryList $directoryList,
        Session $customerSession,
        Data $helper,
        array $data = []
    ) {
        $this->_mediaDirectory = $directoryList->getPath(DirectoryList::MEDIA);
        $this->customerSession = $customerSession;
        $this->helper = $helper;
        parent::__construct($context, $data);
    }

    public function isRestrictedGroup()
    {
        $customerGroupId = $this->customerSession->getCustomerGroupId();
        $restrictedCustomerGroups = [52, 53, 54, 55, 56];

        return in_array($customerGroupId, $restrictedCustomerGroups);
    }

     /**
     * @return string
     */
    public function getCustomerType()
    {
        if ($this->customerType === null) {
            $this->customerType = $this->helper->getCustomerType();
        }
        return $this->customerType;
    }
}
