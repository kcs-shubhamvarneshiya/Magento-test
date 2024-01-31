<?php


namespace Lyonscg\AddressRestrictions\Block\Adminhtml\AddressDeleteLog;

use Magento\Framework\Registry;

class ViewWrapper extends \Magento\Backend\Block\Widget\Container
{
    /**:
     * ViewWrapper constructor.
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        Registry $registry,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->registry = $registry;
    }

    /**
     * Add back button
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->buttonList->add(
            'back',
            [
                'label' => __('Back'),
                'onclick' => "setLocation('" . $this->_urlBuilder->getUrl('*/*/') . "')",
                'class' => 'back'
            ]
        );
    }

    /**
     * @return \Lyonscg\AddressRestrictions\Api\Data\AddressDeleteLogInterface|null
     */
    public function getLog()
    {
        return $this->registry->registry('current_address_delete_log');
    }

    /**
     * Header text getter
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        if ($log = $this->getLog()) {
            return __('Address Delete Log #%1', $log->getId());
        }
        return __('Address Delete Log');
    }
}
