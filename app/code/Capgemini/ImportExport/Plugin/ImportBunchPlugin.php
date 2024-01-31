<?php
declare(strict_types=1);

namespace Capgemini\ImportExport\Plugin;

use Capgemini\ImportExport\Helper\Info;
use Magento\CatalogImportExport\Model\Import\Product;
use Magento\Framework\Serialize\SerializerInterface;

/**
 * Class ImportBunchPlugin
 * @package Capgemini\ImportExport\Plugin
 */
class ImportBunchPlugin
{

    const COL_CATEGORY = Product::COL_CATEGORY;

    protected array $categoryFields = [
        Info::COL_FUNCTION,
        Info::COL_DESIGNER,
        Info::COL_NEWITEM,
        Info::COL_DISCOUNT,
        Info::COL_STYLE,
        Info::COL_ANNEX
    ];

    /**
     * @var Info
     */
    protected Info $helper;

    /**
     * @var SerializerInterface
     */
    protected SerializerInterface $serializer;

    public function __construct(
        Info $helper,
        SerializerInterface $serializer
    )
    {
        $this->helper  = $helper;
        $this->serializer = $serializer;
    }

    /**
     * Get next bunch of validated rows.
     *
     * @param $subject
     * @param $bunch
     * @return array|null
     */
    public function afterGetNextUniqueBunch($subject, $bunch): ?array
    {
        $data = $this->helper->getRequest()->getPostValue();

        if ($data && isset($data['entity']) && $data['entity'] == $this->helper::DEFAULT_IMPORT_PRODUCT_ENTITY_CODE) {
            return $bunch;
        }
        if ($bunch) {
            foreach ($bunch as $rowNum => $rowData) {
                if (!isset($rowData['basecode'])) break;
                foreach ($rowData as $fieldName => $fieldValue) {
                    if ((str_contains($fieldName, 'labelcd')) && (!empty($fieldValue))) {
                        $ddCode = substr($fieldName, 0, 3);
                        $configurableAttributeValue = $rowData[$ddCode . '_data'];
                        $configurableAttributeCode = $fieldValue;
                        $rowData[$configurableAttributeCode] = $configurableAttributeValue;
                        unset($rowData[$ddCode . '_data']);
                    }
                }
                $rowData['name'] = $rowData['description'];
                $rowData['short_description'] = $rowData['description'];

                if ($rowData['tsonfile'] && (!empty($rowData['docname']))) {
                    if ($rowData['product_websites'] == 'uk' || $rowData['product_websites'] == 'eu') {
                        $rowData['ts_docname'] =  'TS_' . $rowData['docname'] . '_EU.pdf';
                    }
                    else {
                        $rowData['ts_docname'] =  'TS_' . $rowData['docname'] . '.pdf';
                    }
                }

                if ($rowData['igonfile'] && (!empty($rowData['docname']))) {
                    $rowData['ig_docname'] =  'IG_' . $rowData['docname'] . '.pdf';
                }

                if ($rowData['cbonfile'] && (!empty($rowData['docname']))) {
                    $rowData['cad_docname'] =  'CB_' . $rowData['basecode'] . '.dwg';
                }

                // just in order to be sure we are not rewriting core 'product_website' key (that core Magento is going to use further)
                // we'll use product_websites_local key for our custom purposes
                $rowData['product_websites_local'] = $rowData['product_websites'] ?? 'base';
                if (!empty($rowData['docname'])) {
                    if (!strpos($rowData['docname'], '.zip')
                        && !strpos($rowData['docname'], '.pdf')) {
                        $rowData['docname'] = str_replace(array(' ', '/'), '', $rowData['docname']);
                        $rowData['image'] = $rowData['docname'] . '.png';
                        $rowData['small_image'] = $rowData['docname'] . '.png';
                        $rowData['thumbnail'] = $rowData['docname'] . '.png';
                    }
                }

                if (isset($rowData['basecode'])) {
                    $rowData['visibility'] = 'Not Visible Individually';
                } else {
                    $rowData['visibility'] = 'Catalog Search';
                }
                $categoryData['product_websites_local'] = $rowData['product_websites_local'];
                $categoryData[self::COL_CATEGORY] = $rowData['category'];
                foreach ($this->categoryFields as $categoryField) {
                    $categoryData[$categoryField] = $rowData[$categoryField] ?? false;
                }
                $categoriesString = $this->helper->mapFeedCategoryToMagentoCategory($categoryData);
                if (isset($categoriesString[self::COL_CATEGORY])) {
                    $rowData[self::COL_CATEGORY] = $categoriesString[self::COL_CATEGORY];
                }

                if (!empty($rowData['carton1weight'])){
                    $weightArray[] = $rowData['carton2weight'];
                }
                if (!empty($rowData['carton1dimensionalweight'])){
                    $weightArray[] = $rowData['carton1dimensionalweight'];
                }
                if (!empty($rowData['carton2weight'])){
                    $weightArray[] = $rowData['carton2weight'];
                }
                if (!empty($rowData['carton2dimensionalweight'])){
                    $weightArray[] = $rowData['carton2dimensionalweight'];
                }

                $weight = isset($weightArray) ? max($weightArray) : 0;

                $rowData['weight'] = $weight;

                $bunch[$rowNum] = $rowData;
            }
        }

        return $bunch;
    }

    /**
     * @param $value
     * @return array
     */
    protected function getRange($value): array
    {

        preg_match_all('/((?:[0-9]+,)*[0-9]+(?:\.[0-9]+)?)/', $value, $matches);

        $result = array();

        if (!empty($matches[0])) {
            foreach ($matches[0] as $val) {
                $minRange = 0;
                $maxRange = 5;

                for ($i = 0; $i < 999; $i++) {
                    if (($val >= $minRange) && ($val <= ($maxRange-1)+.99)) {
                        $result[] = $minRange . '" - ' . $maxRange . '"';

                        break;
                    }
                    $minRange += 5;
                    $maxRange += 5;
                }
            }
        }

        return $result;
    }
}
