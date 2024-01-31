<?php

namespace Capgemini\BloomreachWidget\Block;

use Capgemini\BloomreachWidget\Helper\Data as ModuleHelper;
use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;

class Widget extends Template implements BlockInterface
{
    const ALLOWED_PAGES = [
        'catalog_product_view'                              => 'product',
        'catalog_category_view'                             => 'category',
        'capgemini_bloomreach_category_proxy_category_view' => 'category'
    ];
    const DISPLAY_TYPES = [
        'related_categories' => 'related-category',
        'related_items'      => 'related-item',
        'related_products'   => 'more-results'
    ];
    /**
     * @var string
     */
    private $pageType;
    /**
     * @var array|mixed|null
     */
    private $displayType;
    /**
     * @var array
     */
    private $cachedResponses;
    /**
     * @var ModuleHelper
     */
    private $moduleHelper;

    public function __construct(Template\Context $context, ModuleHelper $moduleHelper, array $data = [])
    {
        parent::__construct($context, $data);

        if ($moduleHelper->isEnabled()) {
            $fullActionName = $moduleHelper->getFullActionName();

            if (in_array($fullActionName, array_keys(self::ALLOWED_PAGES))) {
                $this->moduleHelper = $moduleHelper;
                $this->pageType = self::ALLOWED_PAGES[$fullActionName];
            }
        }
    }

    public function _beforeToHtml()
    {
        if ($this->pageType) {
            switch ($this->displayType = $this->getData('display_type') ?? '') {
                case self::DISPLAY_TYPES['related_categories']:
                    $this->setTemplate('categories.phtml');

                    break;
                case self::DISPLAY_TYPES['related_items']:
                    $this->setTemplate('items.phtml');

                    break;
                case self::DISPLAY_TYPES['related_products']:
                    $this->setTemplate('products.phtml');

                    break;
                default:
                    $this->setTemplate('');
            }
        }

        return parent::_beforeToHtml();
    }

    public function obtainData()
    {
        $baseUrlParameter = $this->getData('base_url_parameter') ?: $this->moduleHelper->getBaseUrlParameter();
        $data = $this->moduleHelper->callApi($this->pageType, $baseUrlParameter);

        return $data[$this->displayType] ?? [];
    }

    public function widgetTitle()
    {
        return $this->getTitle();
    }

    public function isShowDescription()
    {
        return $this->moduleHelper->isItemDescriptionEnabled();
    }
}
