<?php /** @noinspection PhpDeprecationInspection */
/**
 * Capgemini_TechnicalResources
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\TechnicalResources\Block;

use Capgemini\TechnicalResources\Helper\Data;
use Capgemini\TechnicalResources\Model\AttributeValueParser;
use Lyonscg\Catalog\Helper\Data as BaseChildProductHelper;
use Magento\Catalog\Block\Product\View\Description;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template\Context;

class Resources extends Description
{
    /**
     * @var string
     */
    protected $_template = 'Capgemini_TechnicalResources::resources.phtml';

    /**
     * @var array
     */
    protected array $resources = [];

    /**
     * @var AttributeValueParser
     */
    protected AttributeValueParser $attributeValueParser;

    /**
     * @var Data
     */
    protected Data $helper;

    /**
     * @var BaseChildProductHelper
     */
    protected BaseChildProductHelper $baseChildProductHelper;

    /**
     * @var array
     */
    protected $childProducts;

    /**
     * @param AttributeValueParser $attributeValueParser
     * @param Data $helper
     * @param Registry $registry
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        AttributeValueParser   $attributeValueParser,
        Data                   $helper,
        BaseChildProductHelper $baseChildProductHelper,
        Registry               $registry,
        Context                $context,
        array                  $data = []
    )
    {
        parent::__construct(
            $context,
            $registry,
            $data
        );
        $this->attributeValueParser = $attributeValueParser;
        $this->helper = $helper;
        $this->baseChildProductHelper = $baseChildProductHelper;
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @return array
     */
    public function getTechResources(\Magento\Catalog\Model\Product $product): array
    {
        $resources = $this->attributeValueParser->process($product);
        $result = [];
        foreach ($resources as $resource) {
            $result[] = [
                'label' => $resource['label'],
                'url' => $this->getFileUrl($resource['filepath'])
            ];
        }

        return $result;
    }

    /**
     * @param $file
     * @return string
     */
    public function getFileUrl($file): string
    {
        return str_replace('/pub', '', '/' . $this->helper->getResourcePath() . '/' . $file);
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @return string
     */
    public function getUrlDownloadAll(\Magento\Catalog\Model\Product $product): string
    {
        return $this->getUrl(
            'tresources/download',
            [
                'product_id' => $product->getId()
            ]
        );
    }

    /**
     * Gep base product for technical resources
     *
     * @return \Magento\Catalog\Model\Product
     */
    public function getBaseProduct()
    {
        $product = $this->getProduct();
        if ($product->getTypeId() == 'configurable') {
            $product = $this->baseChildProductHelper->getChildProduct($product);
        }
        return $product;
    }

    /**
     * Configuration in JSON format
     *
     * @return string
     */
    public function getConfigJson()
    {
        $productsConfig = [];
        $baseProduct = $this->getBaseProduct();
        $childProducts = $this->getChildProducts($this->getProduct());

        if (count($childProducts) > 0) {
            foreach ($childProducts as $product) {
                $productsConfig[$product->getId()] = $this->getProductConfig($product);
            }
        } else {
            $productsConfig[$baseProduct->getId()] = $this->getProductConfig($baseProduct);
        }

        $config = [
            'productsConfig' => $productsConfig,
            'baseProductId' => $baseProduct->getId()
        ];
        return json_encode($config);
    }

    /**
     * Get product configuration
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return array
     */
    protected function getProductConfig(\Magento\Catalog\Model\Product $product)
    {
        return [
            'techResources' => $this->getTechResources($product),
            'downloadAllUrl' => $this->getUrlDownloadAll($product)
        ];
    }

    /**
     * Get child products
     */
    protected function getChildProducts(\Magento\Catalog\Model\Product $product) {
        if ($product->getTypeId() !== \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE) {
            return [];
        } else {
            if (!$this->childProducts) {
                $childProducts = $product->getTypeInstance()->getUsedProductCollection($product);
                $childProducts->addAttributeToSelect($this->helper->getAttributeCode());
                $this->childProducts = $childProducts->getItems();
            }
            return $this->childProducts;
        }
    }

    /**
     * @return string
     */
    public function toHtml(): string
    {
        if (!$this->helper->isEnabled()) {
            return '';
        }

        return parent::toHtml();
    }
}
