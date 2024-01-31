<?php

namespace Lyonscg\RequisitionList\Plugin;

use Capgemini\LightBulbs\Helper\Data as BulbsHelper;
use Exception;
use Lyonscg\RequisitionList\Helper\Item as RequisitionListItemHelper;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\RequisitionList\Api\Data\RequisitionListItemInterface;
use Magento\RequisitionList\Block\Requisition\View\Items\Grid;
use Magento\RequisitionList\Model\RequisitionListItem\Merger;
use Magento\RequisitionList\Model\RequisitionListItem\Options\Builder;

class ManageAddingBulbs
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var Merger
     */
    private $itemMerger;
    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var BulbsHelper
     */
    private $bulbsHelper;
    /**
     * @var RequisitionListItemHelper
     */
    private $requisitionListItemHelper;
    /**
     * @var array
     */
    private $nonBulbItems = [];
    /**
     * @var array
     */
    private $bulbsCalculationData = [];

    /**
     * @param ProductRepositoryInterface $productRepository
     * @param Merger $itemMerger
     * @param SerializerInterface $serializer
     * @param BulbsHelper $bulbsHelper
     * @param RequisitionListItemHelper $requisitionListItemHelper
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        Merger $itemMerger,
        SerializerInterface $serializer,
        BulbsHelper $bulbsHelper,
        RequisitionListItemHelper $requisitionListItemHelper
    ) {
        $this->productRepository = $productRepository;
        $this->itemMerger = $itemMerger;
        $this->serializer = $serializer;
        $this->bulbsHelper = $bulbsHelper;
        $this->requisitionListItemHelper = $requisitionListItemHelper;
    }

    /**
     * @param Grid $subject
     * @param array $result
     * @return RequisitionListItemInterface[]
     */
    public function afterGetRequisitionListItems(Grid $subject, array $result): array
    {
        $this->parseRequisitionList($result);

        foreach ($this->nonBulbItems as $item) {
            $itemProduct = $this->requisitionListItemHelper->getItemSimpleProduct($item);
            $bulbSku = $itemProduct ? (string) $itemProduct->getData('bulb_sku') : '';

            if (!isset($this->bulbsCalculationData[$bulbSku])) {
                try {
                    $bulb = $this->productRepository->get($bulbSku);
                    $bulbItem = $this->createFakeBulbItem($bulb);
                    $result = $this->itemMerger->mergeItem($result, $bulbItem);
                } catch (Exception $exception) {
                    $this->bulbsHelper->logError('\Lyonscg\RequisitionList\Plugin\ManageAddingBulbs: ' . $exception->getMessage());

                    continue;
                }
            }

            $this->bulbsCalculationData[$bulbSku][$item->getId()] = (int) $itemProduct->getData('bulb_qty');
        }

        $bulbsCalculationData = $this->serializer->serialize($this->bulbsCalculationData);
        $subject->setData('bulbs_calculation_data', $bulbsCalculationData);

        return $result;
    }

    /**
     * @param RequisitionListItemInterface[] $items
     * @return void
     */
    private function parseRequisitionList(array $items)
    {
        foreach ($items as $item) {
            $sku = $item->getSku();
            if ($this->bulbsHelper->isBulb($sku)) {
                $this->bulbsCalculationData[$sku] = [];
                $item->setData('is_bulb_product', true);
            } else {
                $this->nonBulbItems[] = $item;
                $item->setData('is_bulb_product', false);
            }
        }
    }

    /**
     * @param ProductInterface $bulb
     * @return RequisitionListItemInterface
     * @throws Builder\ConfigurationException
     * @throws LocalizedException
     */
    private function createFakeBulbItem(ProductInterface $bulb): RequisitionListItemInterface
    {
        static $fakeId = -1;

        $bulbName = $bulb->getName();

        $bulbItem = $this->requisitionListItemHelper->createNewStandardItemFromProduct($bulb);
        $bulbItem->setData('name', $bulbName);
        $bulbItem->setData('is_bulb_product', true);
        $bulbItem->setId($fakeId--);

        return $bulbItem;
    }
}
