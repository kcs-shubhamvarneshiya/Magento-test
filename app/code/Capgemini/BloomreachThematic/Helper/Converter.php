<?php

namespace Capgemini\BloomreachThematic\Helper;

use Magento\Catalog\Api\Data\EavAttributeInterface;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory;
use Capgemini\BloomreachThematic\Helper\Data as ModuleHelper;
use Magento\Eav\Model\Entity\Attribute\AbstractAttribute;

class Converter
{
    private const STATIC_BR_TO_MAGE_PARAM_MAPPING = [
        'pid'               => 'sku',
        'skuid'             => 'sku',
        'title'             => 'name',
        'categories'        => 'category_ids',
        'category'          => 'category',
        'variants'          => 'variants',
        'thumb_image'       => 'br_product_image',
        'sku_swatch_images' => 'br_product_image',
        'url'               => 'br_product_url',
        'sku_price'         => 'price',
        'sale_price'        => 'special_price',
        'sku_sale_price'    => 'special_price',
        'badge'             => 'badge',
        'detail_description' => 'detail_description'
    ];

    private array $fullBrToMagentoParamMapping = [];

    private array $facetsValueToLabelMapping = [];

    private Data $moduleHelper;

    public function __construct(
        CollectionFactory $productAttributeCollectionFactory,
        ModuleHelper $moduleHelper
    ) {
        $this->moduleHelper = $moduleHelper;
        $this->fillMappings($productAttributeCollectionFactory);
    }

    /**
     * @param array $brProductData
     * @return array
     */
    public function convertProductData(array $brProductData): array
    {
        $magentoProductData = [];

        foreach ($brProductData as $code => $value) {
            $magentoCode = $this->toMage($code);

            if (!$magentoCode) {

                continue;
            }

            if (is_array($value)) {
                $value = $this->manageComplexValues($code, $value);
            }

            $magentoProductData[$magentoCode] = $value;
        }

        return $magentoProductData;
    }

    /**
     * @param $facetFieldName
     * @return string|false
     */
    public function getBucketName($facetFieldName): string|false
    {
        $bucketName = $this->toMage($facetFieldName);

        return $bucketName ? $bucketName . '_bucket' : $bucketName;
    }

    public function convertFacetData(string $facetFieldName, array $facetFieldDatum)
    {
        $facetFieldName = $this->toMage($facetFieldName);

        switch ($facetFieldName) {
            case 'category':

                return [
                    'value'   => $facetFieldDatum['cat_id'],
                    'metrics' => [
                        'value' => $facetFieldDatum['cat_id'],
                        'count' => $facetFieldDatum['count']
                    ]
                ];
            case 'price':
                $start = $facetFieldDatum['start'] ?? '';
                $end = $facetFieldDatum['end'] ?? '';
                $start = str_replace('*', '', $start);
                $value = $start . '_' . $end;

                if ($value === '_') {

                    return [
                        'value'   => 'data',
                        'metrics' => [
                            'value' => 'data',
                            'min'   => $facetFieldDatum['min'],
                            'max'   => $facetFieldDatum['max'],
                            'count' => $facetFieldDatum['count']
                        ]
                    ];
                } else {

                    return [
                        'value'   => $value,
                        'metrics' => [
                            'value' => $value,
                            'count' => $facetFieldDatum['count']
                        ]
                    ];
                }
            default:
                $value = $this->getFacetValueByLabel($facetFieldName, $facetFieldDatum['name']);

                return [
                    'value'   => $value,
                    'metrics' => [
                        'value' => $value,
                        'count' => $facetFieldDatum['count']
                    ]
                ];
        }
    }

    public function getAvailableFacets()
    {
        return array_keys($this->facetsValueToLabelMapping);
    }

    public function getFacetLabelByValue($facetName, $value)
    {
        return $this->facetsValueToLabelMapping[$facetName][$value] ?? false;
    }

    public function getFacetValueByLabel($facetName, $label)
    {
        $optionData = $this->facetsValueToLabelMapping[$facetName] ?? false;

        return $optionData ? array_search($label, $optionData, true) : $optionData;
    }

    private function toMage(string $brValue): string|bool
    {
        return $this->fullBrToMagentoParamMapping[$brValue] ?? false;
    }

    private function fillMappings(CollectionFactory $productAttributeCollectionFactory): void
    {
        $this->fullBrToMagentoParamMapping = self::STATIC_BR_TO_MAGE_PARAM_MAPPING;
        $productAttributes = $productAttributeCollectionFactory->create()
            ->addFieldToFilter(EavAttributeInterface::IS_FILTERABLE, ['in' => [1, 2]]);

        /** @var AbstractAttribute $attribute */
        foreach ($productAttributes as $attribute) {
            try {
                $options = $attribute->getSource()->getAllOptions();
                $optionData = array_column($options, 'label', 'value');
            } catch (\Exception $exception) {
                $this->moduleHelper->logWarning($exception->getMessage());
                $optionData = false;
            }

            $attributeCode = $attribute->getAttributeCode();

            $this->facetsValueToLabelMapping[$attributeCode] = $optionData;
            $this->fullBrToMagentoParamMapping[$attributeCode] = $attributeCode;
            $this->fullBrToMagentoParamMapping[$attribute->getStoreLabel()] = $attributeCode;
        }
    }

    /**
     * @param string $code
     * @param mixed $value
     * @return mixed
     */
    private function manageComplexValues(string $code, array $value): mixed
    {
        return $value;
    }
}
