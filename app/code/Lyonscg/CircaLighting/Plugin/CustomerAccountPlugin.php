<?php

namespace Lyonscg\CircaLighting\Plugin;

class CustomerAccountPlugin
{
    /**
     * @var \Magento\Framework\View\LayoutInterface
     */
    protected $layout;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Magento\Customer\Model\Group
     */
    protected $customerGroupCollection;

    /**
     * CustomerAccountPlugin constructor.
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Customer\Model\Group $customerGroupCollection
     * @param \Magento\Framework\View\LayoutInterface $layout
     */
    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Customer\Model\Group $customerGroupCollection,
        \Magento\Framework\View\LayoutInterface $layout
    ) {
        $this->layout = $layout;
        $this->customerSession = $customerSession;
        $this->customerGroupCollection = $customerGroupCollection;
    }

    /**
     * @param $subject
     * @param $result
     * @return mixed
     */
    public function afterGetLinks($subject, $result)
    {
       if ($this->customerSession->isLoggedIn()) {
            $currentGroupId = $this->customerSession->getCustomer()->getGroupId();
            $collection = $this->customerGroupCollection->load($currentGroupId);
            $customerGroupCode = $collection->getCustomerGroupCode();
            $hasTrade = strpos($customerGroupCode,'Trade');
            if (is_bool($hasTrade)) {
                $this->layout->unsetElement('customer-account-navigation-requisition-list-link');
            }
       }

       return $result;
    }
}
