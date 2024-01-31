<?php
namespace Lyonscg\RequisitionList\Block\Index;

/**
 * Class Customer
 * @package Lyonscg\RequisitionList\Block\Index
 */
class Customer extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * Customer constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        array $data = []
    ) {
        $this->customerSession = $customerSession;
        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function getCustomerFirstName()
    {
        if ($this->customerSession->isLoggedIn()) {
            try {
                return $this->customerSession->getCustomerData()->getFirstName();
            } catch (\Exception $e) {

            }
        }
        return '';
    }

    /**
     * @return string
     */
    public function getCustomerLastName()
    {
        if ($this->customerSession->isLoggedIn()) {
            try {
                return $this->customerSession->getCustomerData()->getLastName();
            } catch (\Exception $e) {

            }
        }
        return '';
    }
}
