<?php

namespace Capgemini\Company\Model\Config\Source;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class BusinessType implements \Magento\Framework\Option\ArrayInterface
{
    const XML_PATH_BUSINESS_TYPES = 'company/general/business_types';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * BusinessType constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    protected function _getBusinessTypes()
    {
        $businessTypesRaw = $this->scopeConfig->getValue(self::XML_PATH_BUSINESS_TYPES, ScopeInterface::SCOPE_STORE);
        $businessTypesRaw = preg_split('/\r\n|\r|\n/', $businessTypesRaw);
        $businessTypes = [];
        foreach ($businessTypesRaw as $businessType) {
            $businessTypes[$businessType] = $businessType;
        }
        // reserved for internal use
        if (isset($businessTypes['other'])) {
            unset($businessTypes['other']);
        }
        return $businessTypes;
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $optionArray = [];
        foreach ($this->_getBusinessTypes() as $code => $label) {
            $optionArray[] = ['value' => $code, 'label' => $label];
        }
        return $optionArray;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return $this->_getBusinessTypes();
    }

    /**
     * @return array
     */
    public function getBusinessTypes()
    {
        return $this->_getBusinessTypes();
    }
}
