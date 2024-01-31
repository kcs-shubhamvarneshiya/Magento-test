<?php


namespace Lyonscg\AddressRestrictions\Block\Adminhtml\AddressDeleteLog;

use Magento\Framework\Registry;

class View extends \Magento\Backend\Block\Template
{
    /**
     * Path to template file in theme.
     *
     * @var string
     */
    protected $_template = 'Lyonscg_AddressRestrictions::addressdeletelog/view.phtml';

    /**
     * @var Registry
     */
    private $registry;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        Registry $registry,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->registry = $registry;
    }

    /**
     * @return \Lyonscg\AddressRestrictions\Api\Data\AddressDeleteLogInterface|null
     */
    public function getLog()
    {
        return $this->registry->registry('current_address_delete_log');
    }
}
