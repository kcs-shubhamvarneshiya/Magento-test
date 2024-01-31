<?php

namespace Capgemini\ImportExport\Plugin;

/**
 * Class parseMultiselectOptionsPlugin
 * @package Capgemini\ImportExport\Plugin
 */
class ParseMultiselectOptionsPlugin
{
    /**
     * @var \Capgemini\ImportExport\Helper\Info
     */
    protected $importHelper;

    public function __construct(
        \Capgemini\ImportExport\Helper\Info $importHelper
    )
    {
        $this->importHelper = $importHelper;
    }

    /**
     * @param $subject
     * @param $values
     * @param $delimiter
     * @return array
     */
    public function beforeParseMultiselectValues($subject, $values)
    {
        $delimiter = ',';
        $values = str_replace(array(", ", " ,", " , ") , ",", $values);

        return [$values, $delimiter];
    }

    public function beforeValidateRow($subject, $rowData, $rowNum)
    {
        $data = $this->importHelper->getRequest()->getPostValue();

        if ($data && isset($data['entity']) && $data['entity'] == $this->importHelper::DEFAULT_IMPORT_PRODUCT_ENTITY_CODE) {
            return [$rowData, $rowNum];
        }
        foreach ($rowData as $fieldName => $fieldValue){
            //Parcing configurable attributes
            if ((strpos($fieldName,'labelcd') !== false) && (!empty($rowData[$fieldName]))) {
                $ddCode = substr($fieldName, 0, 3);
                if(isset($rowData[$ddCode . '_data'])) {
                    $configurableAttributeValue = $rowData[$ddCode . '_data'];
                    $configurableAttributeCode = $rowData[$fieldName];
                    $rowData[$configurableAttributeCode] = $configurableAttributeValue;
                    unset($rowData[$ddCode . '_data']);
                }
            }
        }

        return [$rowData, $rowNum];
    }
}
