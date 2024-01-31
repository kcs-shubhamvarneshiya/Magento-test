<?php

namespace Capgemini\LightBulbs\Helper;

use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\AbstractAttribute;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\RequisitionList\Api\Data\RequisitionListInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const BULB_SKU_ATTRIBUTE_CODE = 'bulb_sku';

    protected $productResource;

    protected $productRepository;

    /**
     * @var array
     */
    private $allBulbSkus = [];

    /**
     * @var \Magento\Eav\Model\Config
     */
    private $eavConfig;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    private $resourceConnection;

    /**
     * @var \Lyonscg\RequisitionList\Helper\Item
     */
    private $requisitionListitemHelper;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product $productResource,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Lyonscg\RequisitionList\Helper\Item $requisitionListitemHelper
    ) {
        parent::__construct($context);
        $this->productResource = $productResource;
        $this->productRepository = $productRepository;
        $this->eavConfig = $eavConfig;
        $this->resourceConnection = $resourceConnection;
        $this->requisitionListitemHelper = $requisitionListitemHelper;
    }

    /**
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @param false $store
     */
    public function getNoSidemark($product, $store = false)
    {
        if ($store === false) {
            $store = $product->getStoreId();
        }
        return $this->productResource->getAttributeRawValue($product->getId(), 'no_sidemark', $store);
    }

    public function getNoSidemarkByItem($item)
    {
        try {
            $product = $this->productRepository->get($item->getSku());
            return $this->productResource->getAttributeRawValue(
                $product->getId(),
                'no_sidemark',
                $item->getStoreId()
            );
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @return AbstractAttribute
     * @throws LocalizedException
     */
    public function getBulbSkuAttribute()
    {
        return $this->eavConfig->getAttribute(
            Product::ENTITY,
            self::BULB_SKU_ATTRIBUTE_CODE
        );
    }

    public function isBulb($sku)
    {
        return in_array($sku, $this->getAllBulbSkus());
    }

    /**
     * @param array $items
     * @param string $itemClass
     * @return bool
     * @throws \Exception
     */
    public function isListHasBulb(array $items, string $itemClass)
    {
        switch ($itemClass) {
            case RequisitionListInterface::class:
                return $this->isRequisitionListHasBulb($items);
            default:
                throw new \Exception('Currently there is no method for determining whether a light bulb is present/absent in the list for ' . $itemClass);
        }
    }

    /**
     * @param array $items
     * @param string $itemClass
     * @return bool
     * @throws \Exception
     */
    public function canListHaveBulb(array $items, string $itemClass)
    {
        switch ($itemClass) {
            case RequisitionListInterface::class:
                return $this->canRequisitionListHaveBulb($items);
            default:
                throw new \Exception('Currently there is no method for determining whether items in the list can have light bulbs for ' . $itemClass);
        }
    }

    public function logError($message)
    {
        $this->_logger->error($message);
    }

    /**
     * @return array
     * @throws LocalizedException
     */
    private function getAllBulbSkus()
    {
        if (empty($this->allBulbSkus)) {
            $bulbSkuAttributeId = $this->getBulbSkuAttribute()->getAttributeId();
            $connection = $this->resourceConnection->getConnection();
            $attrValuesTable = $this->resourceConnection->getTableName('catalog_product_entity_varchar');
            $select = $connection->select()
                ->distinct()
                ->from(['av' => $attrValuesTable], 'value')
                ->where('av.attribute_id=?', $bulbSkuAttributeId);
            $this->allBulbSkus = array_diff($connection->fetchCol($select), ['', null]);
        }

        return $this->allBulbSkus;
    }

    /**
     * @param array $items
     * @return bool
     * @throws LocalizedException
     */
    private function isRequisitionListHasBulb(array $items)
    {
        foreach ($items as $item) {
            $itemProduct = $this->requisitionListitemHelper->getItemSimpleProduct($item);
            if ($itemProduct && $this->isBulb($itemProduct->getSku())) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $items
     * @return bool
     */
    private function canRequisitionListHaveBulb(array $items)
    {
        foreach ($items as $item) {
            $itemProduct = $this->requisitionListitemHelper->getItemSimpleProduct($item);
            $bulbSku = $itemProduct ? (string) $itemProduct->getData('bulb_sku') : '';

            try {
                return !!$this->productRepository->get($bulbSku);
            } catch (NoSuchEntityException $exception) {

                continue;
            }
        }

        return false;
    }

    /**
     * Check if can sell bulbs
     */
    public function canSellBulbs()
    {
        return true;
    }
}
