<?php

namespace Capgemini\Configurable\Block\Configurable;
use \Magento\ConfigurableProduct\Model\ConfigurableAttributeData;
use \Magento\ConfigurableProduct\Helper\Data as ConfigurableHelper;
use \Magento\Catalog\Model\Product\Attribute\Source\Status;
use \Magento\Store\Model\ScopeInterface;

class Children extends \Magento\Catalog\Block\Product\View\AbstractView
{
    const SHOW_OOS_PATH = 'cataloginventory/options/show_out_of_stock';

    const DETAIL_DESCRIPTION = 'detail_description';

    /**
     * @var ConfigurableAttributeData
     */
    protected $configurableAttributeData;

    /**
     * @var ConfigurableHelper
     */
    protected $configurableHelper;

    /**
     * @var \Magento\Catalog\Model\Product
     */
    protected $currentProduct = null;

    /**
     * @var \Magento\Eav\Model\Config
     */
    protected $eavConfig;

    /**
     * Children constructor.
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Framework\Stdlib\ArrayUtils $arrayUtils
     * @param ConfigurableAttributeData $configurableAttributeData
     * @param ConfigurableHelper $configurableHelper
     * @param \Magento\Eav\Model\Config $eavConfig
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Stdlib\ArrayUtils $arrayUtils,
        ConfigurableAttributeData $configurableAttributeData,
        ConfigurableHelper $configurableHelper,
        \Magento\Eav\Model\Config $eavConfig,
        array $data = []
    ) {
        parent::__construct($context, $arrayUtils, $data);
        $this->configurableAttributeData = $configurableAttributeData;
        $this->configurableHelper = $configurableHelper;
        $this->eavConfig = $eavConfig;
    }

    /**
     * Allows reuse of this block in the CLP
     * @param \Magento\Catalog\Model\Product $product
     * @return $this
     */
    public function setProduct(\Magento\Catalog\Model\Product $product)
    {
        $this->currentProduct = $product;
        return $this;
    }

    /**
     * @return \Magento\Catalog\Model\Product|null
     */
    public function getProduct()
    {
        if ($this->currentProduct !== null) {
            return $this->currentProduct;
        } else {
            return parent::getProduct();
        }
    }

    public function isConfigurable()
    {
        return $this->getProduct()->getTypeId() === 'configurable';
    }

    /**
     * @return \Magento\Catalog\Model\Product
     */
    public function getConfigurableProduct()
    {
        $product = $this->getProduct();
        if ($product->getTypeId() !== 'configurable') {
            throw new \Exception('Invalid product type: ' . $product->getTypeId());
        }
        return $product;
    }

    protected function getAllowedChildren()
    {
        $attribute = $this->eavConfig->getAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            self::DETAIL_DESCRIPTION
        );
        if ($attribute && $attribute->getId()) {
            $attributeIds = [$attribute->getId()];
        } else {
            $attributeIds = null;
        }
        $showOos = $this->canShowOutOfStock();
        $configurable = $this->getConfigurableProduct();
        $children = $configurable->getTypeInstance()->getUsedProducts($configurable, $attributeIds);
        $result = [];
        foreach ($children as $child) {
            if ((int)$child->getStatus() === Status::STATUS_ENABLED) {
                if ($showOos || $child->isSalable()) {
                    $result[] = $child;
                }
            }
        }
        return $result;
    }

    protected function canShowOutOfStock()
    {
        return $this->_scopeConfig->isSetFlag(
            self::SHOW_OOS_PATH,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Array format is as follows:
     *      [
     *          $productId => [
     *              'product' => $product,
     *              'attributes' => [
     *                  $attributeId => $attributeValue,
     *                  ...
     *              ]
     *          ],
     *          ...
     *      ]
     * @return array
     */
    public function getConfigurableChildren()
    {
        $configurable = $this->getConfigurableProduct();
        $result = [];

        $children = $this->getAllowedChildren();
        $options = $this->configurableHelper->getOptions($configurable, $children);
        $first = true;

        foreach ($children as $child) {
            $result[$child->getId()] = [
                'product' => $child,
                'is_active' => $first,
            ];
            $first = false;
        }
        // might need to get the attribute labels as well?

        if (isset($options['index'])) {
            foreach ($options['index'] as $productId => $productAttributes) {
                if (isset($result[$productId])) {
                    $result[$productId]['attributes'] = $productAttributes;
                }
            }
        } else {
            foreach ($result as $productId => $data) {
                $result[$productId]['attributes'] = [];
            }
        }
        return $result;
    }
}
