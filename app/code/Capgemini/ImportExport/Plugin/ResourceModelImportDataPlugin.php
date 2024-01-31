<?php
declare(strict_types=1);

namespace Capgemini\ImportExport\Plugin;

use Magento\Framework\App\RequestInterface;
use Capgemini\ImportExport\Helper\Info;
use Magento\ImportExport\Model\ResourceModel\Import\Data;

/**
 * Class ResourceModelImportDataPlugin
 * @package Capgemini\ImportExport\Plugin
 */
class ResourceModelImportDataPlugin
{

    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * @param RequestInterface $request
     */
    public function __construct(
        RequestInterface $request
    )
    {
        $this->request = $request;
    }

    /**
     * Create configurable products from simples
     * 
     * @param Data $subject
     * @param $result
     * @return array|mixed
     */
    public function afterGetNextUniqueBunch(Data $subject, $result): mixed
    {
        if (!empty($result)) {
            if ($this->request->getParam('entity') == Info::CUSTOM_IMPORT_PRODUCT_ENTITY_CODE) {
                $configurableRows = [];
                foreach ($result as &$row) {
                    $row['status'] = 1;
                    $configurableRow = [];
                    $configurableRow['sku'] = $row['basecode'];
                    $configurableRow['status'] = 1;
                    $configurableRow['product_type'] = 'configurable';
                    $configurableRow['_attribute_set'] = 'Default';
                    $configurableRow['updatetype'] = 'Update';
                    $configurableRow['name'] = $row['shortdescription'];
                    //$configurableRow['_store'] = $row['_store'];
                    //$configurableRow['product_websites'] = $row['product_websites'];
                    $configurableRow['_product_websites'] = $row['product_websites'];
                    if ($row['basecodedisplayimage'] == 1) {
                        $configurableRow['attribute_set_code'] = 'Default';
                        $configurableRow['visibility'] = 'Catalog, Search';
                        $configurableRow['product_online'] = 1;
                        $configurableRow['price'] = 0;
                        $configurableRow['docname'] = $row['docname'];
                        $configurableRow['doc_name'] = $row['doc_name'];
                        $configurableRow['use_config_manage_stock'] = 0;
                        $configurableRow['manage_stock'] = 1;
                        $configurableRow['is_in_stock'] = 1;
                        $configurableRow['name'] = $row['shortdescription'];
                        $configurableRow['product_websites'] = $row['product_websites'];
                        $configurableRow['categories'] = $row['categories'];
                        $configurableRow['bulb_sku'] = $row['bulb_sku'];
                        $configurableRow['bulb_qty'] = $row['bulb_qty'];
                        $configurableRow['description'] = $row['copyfreeformcreative'];
                        $configurableRow['function'] = $row['function'];
                        $configurableRow['relatives'] = $row['relatives'];
                        $configurableRow['height_filter'] = !empty($row['height']) ? trim($row['height']) : '';
                        $configurableRow['width_filter'] = !empty($row['width']) ? trim($row['width']) : '';
                        $configurableRow['style'] = !empty($row['style']) ? trim($row['style']) : '';
                        $configurableRow['image'] = !empty($row['image']) ? trim($row['image']) : '';
                        if (!empty($row['docname'])) {
                            $configurableRow['docname'] = str_replace(array(' ', '/'), '', $row['docname']);
                            $configurableRow['image'] = $row['docname'] . '.png';
                            $configurableRow['small_image'] = $row['docname'] . '.png';
                            $configurableRow['thumbnail'] = $row['docname'] . '.png';
                        }
                    }
                    $configurableVariationLabels = '';
                    $configurableVariations = 'sku=' . $row['stockcode'] . ',';
                    foreach ($row as $fieldName => $fieldValue) {
                        if (strpos($fieldName, 'labelcd') && !empty($fieldValue)) {
                            $configurableVariationLabels .= (!empty($configurableVariationLabels)) ? ',' : '';
                            $configurableVariationLabels .= $fieldValue . "=" .
                                $row[str_replace('labelcd', 'label', $fieldName)];
                            $configurableVariations .= $fieldValue . "=" . $row[str_replace('labelcd', 'data', $fieldName)] . ",";
                        }
                    }
                    $configurableRow['configurable_variation_labels'] = $configurableVariationLabels;
                    $configurableRow['configurable_variations'] = $configurableVariations;
                    $configurableRow = $this->removeEmptyFields($configurableRow);
                    $configurableRows[] = $configurableRow;
                }
                return array_merge($result, $configurableRows);
            }
        }
        return $result;
    }

    /**
     * @param array $configurable
     * @return array
     */
    private function removeEmptyFields(array $configurable): array
    {
        foreach ($configurable as $key => $value) {
            if (empty($value)) {
                unset($configurable[$key]);
            }
        }
        return $configurable;
    }
}
