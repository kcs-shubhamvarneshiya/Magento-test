<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Capgemini\ImportExport\Plugin;

use Magento\Framework\Serialize\SerializerInterface;
/**
 * Import entity abstract product type model
 *
 * phpcs:disable Magento2.Classes.AbstractApi
 * @api
 *
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @since 100.0.2
 */
class AbstractTypePlugin
{
    const XML_PATH_IMPORT_MAPPING = 'importexport/general/mapping';

    protected $helper;

    protected $serializer;

    public function __construct(
        \Capgemini\ImportExport\Helper\Info $helper,
        SerializerInterface $serializer
    )
    {
        $this->helper = $helper;
        $this->serializer = $serializer;
    }

    /**
     * Return entity custom Fields mapping.
     *
     * @return string[]
     */
    public function afterGetCustomFieldsMapping($subject, $result)
    {
        $data = $this->helper->getRequest()->getPostValue();

        if ($data && isset($data['entity']) && $data['entity'] == $this->helper::DEFAULT_IMPORT_PRODUCT_ENTITY_CODE) {
            return $result;
        }

        $mappingConfig = $this->serializer->unserialize($this->helper->getConfig(self::XML_PATH_IMPORT_MAPPING));
        foreach ($mappingConfig as $key => $map) {
            $fieldsMap[$map['magento_field_id']] = $map['source_field_id'];
            $fieldsMap[$map['magento_field_id']] = $map['source_field_id'];
        }

        return array_merge($result, $fieldsMap);
    }

    /**
     * @param $subject
     * @param array $rowData
     * @param $withDefaultValue
     * @return array
     */
    public function beforePrepareAttributesWithDefaultValueForSave($subject, array $rowData, $withDefaultValue)
    {
        foreach($rowData as $attrCode => $attrValue) {
            if (!is_array($rowData[$attrCode])) {
                $rowData[$attrCode] = !empty($rowData[$attrCode]) ? trim($rowData[$attrCode]) : '';
            }
        }

        return [$rowData, $withDefaultValue];
    }
}
