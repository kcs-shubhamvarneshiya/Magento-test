<?php

namespace Lyonscg\RequisitionList\Helper;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\RequisitionList\Api\Data\RequisitionListItemInterface;
use Magento\RequisitionList\Model\OptionsManagement;
use Magento\RequisitionList\Model\RequisitionListItem;
use Magento\RequisitionList\Model\RequisitionListItem\Locator;
use Magento\RequisitionList\Model\RequisitionListItem\Options\Builder;

class Item
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var OptionsManagement
     */
    private $optionsManagement;

    /**
     * @var Builder
     */
    private $optionsBuilder;

    /**
     * @var Locator
     */
    private $requisitionListItemLocator;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        OptionsManagement $optionsManagement,
        Builder $optionsBuilder,
        Locator $requisitionListItemLocator
    ) {
        $this->productRepository = $productRepository;
        $this->optionsManagement = $optionsManagement;
        $this->optionsBuilder = $optionsBuilder;
        $this->requisitionListItemLocator = $requisitionListItemLocator;
    }

    /**
     * @param RequisitionListItem $item
     * @return ProductInterface|null
     */
    public function getItemSimpleProduct(RequisitionListItem $item)
    {
        $itemOptions = $item->getOptions();
        $buyRequestData = $this->optionsManagement->getInfoBuyRequest($item);

        if (isset($itemOptions['simple_product'])) {
            $itemProductId = $itemOptions['simple_product'];
        } elseif (isset($buyRequestData['selected_configurable_option'])) {
            $itemProductId = $buyRequestData['selected_configurable_option'];
        } elseif (isset($buyRequestData['product'])) {
            $itemProductId = $buyRequestData['product'];
        } else {
            $itemProductId = null;
        }

        if ($itemProductId) {
            try {

                return $this->productRepository->getById($itemProductId);
            } catch (NoSuchEntityException $exception) {

                return null;
            }
        } else {
            try {

                return $this->productRepository->get($item->getSku());
            } catch (NoSuchEntityException $exception) {

                return null;
            }
        }
    }

    /**
     * @param ProductInterface|Product $product
     * @return RequisitionListItemInterface
     * @throws Builder\ConfigurationException
     * @throws LocalizedException
     */
    public function createNewStandardItemFromProduct($product): RequisitionListItemInterface
    {
        $productId = $product->getId();

        $itemOptions = [];
        $itemOptions['id'] = $productId;
        $itemOptions['selected_configurable_option'] = '';
        $itemOptions['related_product'] = '';
        $itemOptions['item'] = $productId;
        $itemOptions = $this->optionsBuilder->build($itemOptions, 0, false);

        $item = $this->requisitionListItemLocator->getItem(0);
        $item->setOptions($itemOptions);
        $item->setSku($product->getSku());

        return $item;
    }
}
