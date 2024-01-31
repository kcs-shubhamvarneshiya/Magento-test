<?php
/**
 * Capgemini_ProductDimensions
 * php version 7.4.27
 *
 * @category  Capgemini
 * @package   Capgemini_ProductDimensions
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link      https://www.capgemini.com
 */

declare(strict_types=1);

namespace Capgemini\ProductDimensions\Model;

use Capgemini\Dimensions\Model\Converter;
use Lyonscg\Catalog\Helper\Data as ChildProductHelper;

class DimensionsConfig
{
    public const ATTRIBUTE_MAX_COUNT = 14;

    /**
     * @var string
     */
    private string $specificationsAttributePrefix = 'specification_bottom_';

    /**
     * @var string
     */
    private string $dimensionsAttributePrefix = 'dimension_';

    /**
     * Short description
     *
     * @var Converter
     */
    protected Converter $converter;

    /**
     * @var ChildProductHelper
     */
    protected ChildProductHelper $baseChildProductHelper;

    /**
     * @param Converter $converter
     * @param ChildProductHelper $baseChildProductHelper
     */
    public function __construct(
        Converter $converter,
        ChildProductHelper $baseChildProductHelper
    ) {
        $this->converter = $converter;
        $this->baseChildProductHelper = $baseChildProductHelper;
    }

    /**
     * @param $product
     *
     * @return array
     */
    public function getConfig($product): array
    {
        $config['dimensionsConfig'] = [];
        $config['currentProduct'] = $product->getid();
        if ($product->getTypeId() == 'configurable') {
            $baseChild = $this->baseChildProductHelper->getChildProduct($product);
            if ($baseChild) {
                $config['currentProduct'] = $baseChild->getId();
            }
            $products = $product->getTypeInstance()->getUsedProducts($product, null);
            foreach ($products as $product) {
                $config['dimensionsConfig']['specifications'][$product->getId()] = $this->getSpecifications($product);
                $config['dimensionsConfig']['dimensions'][$product->getId()] = $this->getDimensions($product);
            }
        }

        $config['dimensionsConfig']['specifications'][$product->getId()] = $this->getSpecifications($product);
        $config['dimensionsConfig']['dimensions'][$product->getId()] = $this->getDimensions($product);

        return $config;
    }

    /**
     * Short description
     *
     * @param $product
     *
     * @return array
     */
    public function getDimensions($product): array
    {
        $attributesData = $this->getBaseAttributesData(
            $product,
            $this->dimensionsAttributePrefix
        );

        foreach ($attributesData as &$attributeData) {
            $attributeValue = $attributeData['value'];
            $attributeData['metric'] = $this->converter->convert(
                $attributeValue
            );
        }

        return $attributesData;
    }

    /**
     * @param $product
     *
     * @return array
     */
    public function getSpecifications($product): array
    {
        return $this->getBaseAttributesData(
            $product,
            $this->specificationsAttributePrefix
        );
    }

    /**
     * Short description
     *
     * @param $product
     * @param $attributePrefix
     * @return array
     */
    protected function getBaseAttributesData($product, $attributePrefix): array
    {
        $specificationArray = [];
        foreach (range(1, self::ATTRIBUTE_MAX_COUNT) as $attrNum) {
            $attributeCode = $attributePrefix.$attrNum;
            $attributeValue = $product->getData($attributeCode);
            if (!$attributeValue) {
                continue;
            }
            list($parsedLabel, $parsedValue) = $this->getParsedValues($attributeValue);
            if (!$parsedValue) {
                continue;
            }
            $specificationArray[] = [
                'attribute_code' => $attributeCode,
                'attribute_value' => $attributeValue,
                'label' => $parsedLabel,
                'value' => $parsedValue,
                'base64label' => base64_encode($parsedLabel)
            ];
        }

        return $specificationArray;
    }

    /**
     * Short description
     *
     * @param string $attributeValue
     *
     * @return array
     */
    protected function getParsedValues(string $attributeValue): array
    {
        $values = array_map('trim', explode(':', $attributeValue));
        if (count($values) == 1) {
            $values[1] = $values[0];
            $values[0] = '';
        }
        return $values;
    }
}
