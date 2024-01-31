<?php

namespace Lyonscg\Contact\Block;

use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class ContactForm extends \Magento\Contact\Block\ContactForm
{
    const XML_PATH_SEND_FORM = 'contact/contact/form';
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return mixed
     */
    public function getFormId() {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SEND_FORM,
            ScopeInterface::SCOPE_STORE
        );
    }
}
