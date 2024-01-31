<?php
/**
 * Lyonscg_ConfigurableSimple
 *
 * @category  Lyons
 * @package   Lyonscg_ConfigurableSimple
 * @author    Logan Montgomery<logan.montgomery@capgemini.com>
 * @author    Tanya Mamchik<tanya.mamchik@capgemini.com>
 * @copyright Copyright (c) 2020 Lyons Consulting Group (www.lyonscg.com)
 */

namespace Lyonscg\ConfigurableSimple\Block;

use Lyonscg\Catalog\Helper\Config as ConfigHelper;
use Lyonscg\Catalog\Helper\Data;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Visibility as ProductVisibility;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Filesystem\DirectoryList;

class Specifications extends \Magento\Catalog\Block\Product\View\Description
{
    const PDP_SCHEMATIC_FOLDER = 'pdp_schematic';

    const DOC_FOLDER = 'docs';

    const PDP_SCHEMATIC_ATTRIBUTE = 'pdp_schematic';

    /**
     * @var string
     */
    protected $_template = 'Lyonscg_ConfigurableSimple::product/view/specifications.phtml';

    protected $_fileAttributes = [
        'ts_docname' => [
            "css" => "file-pdf file-ts",
            "download_type" => "Specification Sheet"
        ],
        'ig_docname' => [
            "css" => "file-pdf file-ig",
            "download_type" => "Installation Guide"
        ],
        'cad_docname' => [
            "css" => "file-cad",
            "download_type" => "CAD Block"
        ],
        'pdp_3d_rendering' => [
            "css" => "file-dwg",
            "download_type" => "3D Rendering"
        ]
    ];

    /**
     * @var array
     */
    protected $testStrings = [];

    /**
     * @var \Lyonscg\ConfigurableSimple\Helper\Output
     */
    protected $helper;

    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    protected $serializer;

    /**
     * @var array
     */
    protected $additionalTestStrings = [];

    /**
     * @var string[]
     */
    protected $attributesToHide;

    /**
     * @var Data
     */
    protected $catalogHelper;

    /**
     * @var Product|null
     */
    protected $baseChildProduct = null;

    /**
     * @var CollectionFactory
     */
    private $productCollectionFactory;

    /**
     * @var ProductVisibility
     */
    private $catalogProductVisibility;

    /**
     * Specifications constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Lyonscg\ConfigurableSimple\Helper\Output $helper
     * @param \Magento\Framework\Serialize\Serializer\Json $serializer
     * @param Data $catalogHelper
     * @param CollectionFactory $productCollectionFactory
     * @param ProductVisibility $catalogProductVisibility
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry                      $registry,
        \Lyonscg\ConfigurableSimple\Helper\Output        $helper,
        \Magento\Framework\Serialize\Serializer\Json     $serializer,
        Data                                             $catalogHelper,
        CollectionFactory                                $productCollectionFactory,
        ProductVisibility                                $catalogProductVisibility,
        array                                            $data = []
    )
    {
        parent::__construct($context, $registry, $data);
        $this->helper = $helper;
        $this->catalogHelper = $catalogHelper;
        $this->serializer = $serializer;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->catalogProductVisibility = $catalogProductVisibility;
    }

    /**
     * @return bool|string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getSpecificationsDataJson()
    {
        $attributes = [];
        $attributes['configurable'] = $this->_getSpecificationAttributesData($this->getProduct());
        $children = $this->helper->getChildProducts($this->getProduct());
        foreach ($children as $child) {
            /** @var Product $child */
            $id = $child->getId();
            $attributes[$id] = $this->_getSpecificationAttributesData($child, true);
        }
        return $this->serializer->serialize($attributes);
    }

    /**
     * @param Product $product
     * @param boolean $all
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _getSpecificationAttributesData(Product $product, $all = false)
    {
        $data = [];
        foreach (array_merge(
                     $this->helper->getSpecificationAttributes(),
                     array_keys($this->_fileAttributes), ['description', 'pdp_schematic'])
                 as $attributeCode) {
            if (!$all && !$this->helper->shouldAttributeOverride($attributeCode)) {
                continue;
            }
            $data[$attributeCode] = $this->helper->productAttribute($product, $product->getData($attributeCode), $attributeCode);
        }
        if(!empty($data)){
            foreach($data as $i => $attributes) {
                if ($data[$i] == null) {
                    $data[$i] = '';
                }
                if ($data[$i]) {
                    $data[$i] = preg_replace_callback('/([\d.]+)("|in)/i', function($matches) {
                        $value = $matches[1];
                        return round((floor($value) * 2.54)) . 'cm';
                    }, $data[$i]);
                    $data[$i] = preg_replace_callback('/([\d.]+)("|cm)/i', function($matches) {
                        $value = $matches[1];
                        return round((floor($value) / 2.54)) . '"';
                    }, $data[$i]);
                    $data[$i] = preg_replace_callback('/([\d.]+)(\'|ft)/i', function($matches) {
                        $value = $matches[1];
                        return round((floor($value) * 0.3048)) . 'm';
                    }, $data[$i]);
                    $data[$i] = preg_replace_callback('/([\d.]+)(\'|lbs)/i', function($matches) {
                        $value = $matches[1];
                        return round((floor($value) * 0.453592)) . 'kg';
                    }, $data[$i]);
                }
            }
        }
        return $data;
    }

    public function getFileAttributesData(Product $product)
    {
        $data = [];
        foreach ($this->_fileAttributes as $attributeCode => $extraData) {
            $fileName = $this->helper->productAttribute($product, $product->getData($attributeCode), $attributeCode);
            if ($this->productFileExists($fileName)) {
                $extraData['folder_url'] = $this->getMediaUrl() . self::DOC_FOLDER . '/';
                $extraData['file_name'] = $fileName;
                $data[$attributeCode] = $extraData;
            }
            if ($this->product3dFileExists($fileName)) {
                $extraData['folder_url'] = $this->getMediaUrl() . self::DOC_FOLDER . '/3d/';
                $extraData['file_name'] = $fileName;
                $data[$attributeCode] = $extraData;
            }
        }
        return $data;
    }

    /**
     * @param string $attributeId
     * @return mixed|string
     */
    public function getProductCustomAttributeValue($attributeId, $useBaseChildProduct = false)
    {
        $product = ($useBaseChildProduct) ? $this->getBaseChildProduct() : $this->getProduct();
        return (!empty(
        $attribute = $product->getCustomAttribute($attributeId))) ?
            $attribute->getValue() : '';
    }

    /**
     * @param string $attributeId
     * @return string
     */
    public function getCmsBlockHtmlByAttributeValue($attributeId)
    {
        try {
            return (!empty($blockId = $this->getProductCustomAttributeValue($attributeId))) ?
                $this->getLayout()->createBlock('Magento\Cms\Block\Block')->setBlockId($blockId)->toHtml() : '';
        } catch (\Exception $e) {
            return '';
        }
    }

    public function getMediaUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
    }

    /**
     * @param string $splittedLabelValue
     * @return array
     */
    public function explodeToLabelVelue($splittedLabelValue)
    {
        if (strpos($splittedLabelValue, ':') === false) {
            return [
                'label' => '',
                'value' => $splittedLabelValue
            ];
        } else {
            $labelValue = explode(':', $splittedLabelValue, 2);
            return [
                'label' => $labelValue[0],
                'value' => $labelValue[1]
            ];
        }
    }

    /**
     * @param Product $product
     * @return array
     */
    public function getAdditionalData(Product $product)
    {
        $additionalData = $this->helper->getAdditionalData($product);
        foreach ($additionalData as $attributeCode => $data) {
            $processedValue = $this->explodeToLabelVelue($data['value']);
            if (!empty($processedValue['label'])) {
                $additionalData[$attributeCode]['label'] = $processedValue['label'];
            }
            $additionalData[$attributeCode]['value'] = $processedValue['value'];
        }
        if(!empty($additionalData)){
            foreach($additionalData as $data) {
                if (!isset($data['value'])) {
                    continue;
                }
                if (!empty($data['value'])) {
                    $additionalData[$data['code']]['value'] = preg_replace_callback('/([\d.]+)("|in)/i', function($matches) {
                        $value = $matches[1];
                        return round((floor($value) * 2.54)) . 'cm';
                    }, $additionalData[$data['code']]['value']);
                    $additionalData[$data['code']]['value'] = preg_replace_callback('/([\d.]+)("|cm)/i', function($matches) {
                        $value = $matches[1];
                        return round((floor($value) / 2.54)) . '"';
                    }, $additionalData[$data['code']]['value']);
                    $additionalData[$data['code']]['value'] = preg_replace_callback('/([\d.]+)(\'|ft)/i', function($matches) {
                        $value = $matches[1];
                        return round((floor($value) * 0.3048)) . 'm';
                    }, $additionalData[$data['code']]['value']);
                    $additionalData[$data['code']]['value'] = preg_replace_callback('/([\d.]+)(\'|lbs)/i', function($matches) {
                        $value = $matches[1];
                        return round((floor($value) * 0.453592)) . 'kg';
                    }, $additionalData[$data['code']]['value']);
                }
            }
        }
        return $additionalData;
    }

    /**
     * @return Product
     */
    public function getBaseChildProduct()
    {
        if (empty($this->baseChildProduct)) {
            $this->baseChildProduct = $this->catalogHelper->getChildProduct($this->getProduct());
        }
        return $this->baseChildProduct;
    }

    /**
     * @param string $fileName
     * @return bool
     */
    public function productFileExists($fileName)
    {
        $media = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA);
        return $media->isFile(self::DOC_FOLDER . '/' . $fileName);
    }

    /**
     * @param string $fileName
     * @return bool
     */
    public function product3dFileExists($fileName)
    {
        $media = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA);
        return $media->isFile(self::DOC_FOLDER . '/3d/' . $fileName);
    }

    /**
     * @param Product $product
     * @return string
     */
    public function getPdpSchematicImage(Product $product)
    {
        try {
            $fileName = $this->helper->productAttribute(
                $product,
                $product->getData(self::PDP_SCHEMATIC_ATTRIBUTE),
                self::PDP_SCHEMATIC_ATTRIBUTE
            );
            if ($this->productFileExists($fileName)) {
                return $this->getMediaUrl() . self::PDP_SCHEMATIC_FOLDER . '/' . $fileName;
            }
        } catch (\Exception $e) {
            $this->_logger->notice(
                "Lyonscg\ConfigurableSimple\Block\Specifications getPdpSchematicImage() error happened: " .
                $e->getMessage()
            );
        }
        return "";
    }

    /**
     * @param Product $parent
     * @return int
     */
    public function hasRelatives(Product $product)
    {
        $child = $this->catalogHelper->getChildProduct($product);
        $relatives = $this->productCollectionFactory->create()
            ->addAttributeToSelect('relatives')
            ->addFieldToFilter('relatives', ['eq' => $child->getRelatives()])
            ->addFieldToFilter('entity_id', ['neq' => $product->getId()])
            ->addStoreFilter()
            ->setVisibility($this->catalogProductVisibility->getVisibleInCatalogIds());

        return $relatives->getSize();
    }

    public function getRelativeCount(){
        
        $block = $this->getLayout()->createBlock('\Capgemini\ProductDimensions\Block\Product');
        $block->setProduct($this->getProduct());
        return $block->getRelativeCount();
    }
}
