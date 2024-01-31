<?php

namespace Capgemini\Company\Model\Config\Source;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Collection source
 */
class Collection implements \Magento\Framework\Option\ArrayInterface
{
    const XML_PATH = 'company/general/collections';

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

    protected function _getCollections()
    {
        $collectionsRaw = $this->scopeConfig->getValue(self::XML_PATH, ScopeInterface::SCOPE_STORE);
        $collectionsRaw = preg_split('/\r\n|\r|\n/', $collectionsRaw);
        $collections = [];
        foreach ($collectionsRaw as $collection) {
            $collections[$collection] = $collection;
        }
        return $collections;
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $optionArray = [];
        foreach ($this->_getCollections() as $code => $label) {
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
        return $this->_getCollections();
    }
}