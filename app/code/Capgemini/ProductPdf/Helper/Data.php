<?php
/**
 * Capgemini_ProductPdf
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\ProductPdf\Helper;

use Dompdf\Dompdf;
use Lyonscg\Catalog\Helper\Data as SpecHelper;
use Lyonscg\ConfigurableSimple\Helper\Output as SpecOutput;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Helper\Output;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Module\Manager;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Phrase;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use iio\libmergepdf\Merger;
use iio\libmergepdf\Driver\TcpdiDriver;


class Data extends AbstractHelper
{
    public const HTTP_PARAM_ID = 'id';
    public const HTTP_PARAM_PRICING = 'pricing';
    public const HTTP_PARAM_OPTIONS = 'options';

    /**
     * @var Output
     */
    protected $outputHelper;

    /**
     * @var PriceCurrencyInterface
     */
    protected $priceCurrency;

    /**
     * @var Manager
     */
    protected $moduleManager;

    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @param Context $context
     * @param Output $outputHelper
     * @param PriceCurrencyInterface $priceCurrency
     * @param Manager $moduleManager
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(
        Context $context,
        Output $outputHelper,
        PriceCurrencyInterface $priceCurrency,
        Manager $moduleManager,
        ObjectManagerInterface $objectManager
    ) {
        parent::__construct($context);
        $this->outputHelper = $outputHelper;
        $this->priceCurrency = $priceCurrency;
        $this->moduleManager = $moduleManager;
        $this->objectManager = $objectManager;
    }

    /**
     * @return Dompdf|mixed
     */
    public function getPdfWriter()
    {
        return $this->objectManager->create(Dompdf::class);
    }

    /**
     * @param $page1
     * @param $page2
     * @return string
     */
    public function mergePdf($page1, $page2)
    {
        $merger = new Merger(new TcpdiDriver());
        $merger->addRaw($page1);
        $merger->addRaw($page2);
        return $merger->merge();
    }

    /**
     * @param array $array
     * @param array $filter
     * @return array
     */
    public function filterAttrArray(array $array, array $filter): array
    {
        return array_filter($array,
            function ($value, $key) use ($filter) {
                return (isset($value['label']) && in_array($value['label'], $filter));
            },
            ARRAY_FILTER_USE_BOTH
        );
    }

    /**
     * @param ProductInterface $product
     * @return array
     */
    public function getSpecificationsAttributes(ProductInterface $product)
    {
        /**
         * Specifications data from PDP
         */
        if ($this->moduleManager->isEnabled('Lyonscg_ConfigurableSimple')
            && class_exists(SpecOutput::class)) {
            $specOutputHelper = $this->objectManager->create(SpecOutput::class);
            $specHelper = $this->objectManager->create(SpecHelper::class);
            $baseChildProduct = $specHelper->getChildProduct($product);
            return $this->getProductAttributesData(
                $baseChildProduct,
                $specOutputHelper->getSpecificationAttributes(),
                false
            );
        } else {
            return $this->getProductAttributesData($product);
        }
    }

    /**
     * @param ProductInterface $product
     * @param array $attributes
     * @param bool $filterVisible
     * @return array
     */
    public function getProductAttributesData(ProductInterface $product, $attributes = [], $filterVisible = true)
    {
        $data = [];
        $allAttributes = $product->getAttributes();

        foreach ($allAttributes as $attribute) {
            if (!empty($attributes)
                && !in_array($attribute->getAttributeCode(), $attributes)
            ) {
                continue;
            }

            if ($filterVisible && !$attribute->getIsVisibleOnFront()) {
                continue;
            }

            $value = $attribute->getFrontend()->getValue($product);

            if ($value instanceof Phrase) {
                $value = (string)$value;
            } elseif ($attribute->getFrontendInput() == 'price' && is_string($value)) {
                $value = $this->priceCurrency->convertAndFormat($value);
            }

            if (is_string($value) && strlen(trim($value))) {

                $splitted = explode(':', $value);
                $label = $attribute->getStoreLabel();
                $attrValue = $value;

                if (isset($splitted[1])) {
                    list ($splittedLabel, $splittedValue) = $splitted;
                    $label = $splittedLabel;
                    $attrValue = $splittedValue;
                }

                $data[$attribute->getAttributeCode()] = [
                    'label' => $label,
                    'value' => $attrValue,
                    'code' => $attribute->getAttributeCode(),
                ];
            }
        }
        return $data;
    }
}
