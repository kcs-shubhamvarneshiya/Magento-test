<?php
/**
 * RequisitionList Block for PDF Generation
 *
 * @category  Lyons
 * @package   Lyonscg_RequisitionList
 * @author    Mark Hodge <mark.hodge@capgemini.com>
 * @copyright Copyright (c) 2019 Lyons Consulting Group (www.lyonscg.com)
 */
namespace Lyonscg\RequisitionList\Block;

use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Helper\Image;
use Magento\Tax\Helper\Data as TaxHelper;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\Template;
use Magento\Theme\Block\Html\Header\Logo;
use Magento\RequisitionList\Api\RequisitionListRepositoryInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\RequisitionList\Model\RequisitionListItemProduct;
use Magento\RequisitionList\Api\Data\RequisitionListItemInterface;
use Magento\RequisitionList\Model\RequisitionListItem\Validation;
use Magento\RequisitionList\Model\RequisitionListItemOptionsLocator;
use Magento\Framework\App\ObjectManager;
use Magento\RequisitionList\Model\RequisitionList\ItemSelector;
use Magento\Catalog\Model\Product\Configuration\Item\ItemResolverInterface;

/**
 * Class Quote
 * @package Lyonscg\RequisitionList\Block
 */
class Quote extends Template
{
    const LOGO_DIRECTORY = 'company_logo/';

    protected $_template = 'Lyonscg_RequisitionList::pdf.phtml';

    /**
     * @var Validation
     */
    private $validation;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\RequisitionList\Model\RequisitionList\ItemSelector
     */
    private $itemSelector;

    /**
     * @var RequestInterface
     */
    protected $request;

    protected $_logo;

    /**
     * @var RequisitionListItemProduct
     */
    private $requisitionListItemProduct;

    /**
     * @var ItemResolverInterface
     */
    private $itemResolver;

    /**
     * @var RequisitionListItemInterface
     */
    private $item;

    /**
     * @var Image
     */
    protected $imageHelper;

    /**
     * @var TaxHelper
     */
    private $taxHelper;

    /**
     * @var int
     */
    private $itemErrorCount = 0;

    /**
     * @var array
     */
    private $errorsByItemId = [];

    /**
     * @var array
     */
    private $itemErrors = [];

    /**
     * @var RequisitionListRepositoryInterface
     */
    protected $requisitionListRepository;

    /**
     * @var RequisitionListItemOptionsLocator|mixed
     */
    private $itemOptionsLocator;

    /**
     * Quote constructor.
     *
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param Logo $logo
     * @param RequisitionListRepositoryInterface $requisitionListRepository
     * @param RequisitionListItemProduct $requisitionListItemProduct
     * @param Validation $validation
     * @param ItemSelector $itemSelector
     * @param RequestInterface $request
     * @param Image $imageHelper
     * @param ItemResolverInterface|null $itemResolver
     * @param array $data
     * @param RequisitionListItemOptionsLocator|null $itemOptionsLocator
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        Logo $logo,
        RequisitionListRepositoryInterface $requisitionListRepository,
        RequisitionListItemProduct $requisitionListItemProduct,
        Validation $validation,
        ItemSelector $itemSelector,
        RequestInterface $request,
        Image $imageHelper,
        ItemResolverInterface $itemResolver = null,
        array $data = [],
        RequisitionListItemOptionsLocator $itemOptionsLocator = null
    ) {
        parent::__construct($context, $data);
        $this->storeManager = $storeManager;
        $this->_logo = $logo;
        $this->request = $request;
        $this->requisitionListRepository = $requisitionListRepository;
        $this->requisitionListItemProduct = $requisitionListItemProduct;
        $this->validation = $validation;
        $this->itemSelector = $itemSelector;
        $this->imageHelper = $imageHelper;
        $this->taxHelper = $context->getTaxData();
        $this->itemResolver = $itemResolver
            ?? ObjectManager::getInstance()->get(ItemResolverInterface::class);
        $this->itemOptionsLocator = $itemOptionsLocator
            ?? ObjectManager::getInstance()->get(RequisitionListItemOptionsLocator::class);
    }

    /**
     * Get logo image URL
     *
     * @return string
     */
    public function getLogoSrc()
    {
        return $this->_logo->getLogoSrc();
    }

    /**
     * Get requisition list item.
     *
     * @return RequisitionListItemInterface
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * Set requisition list item
     *
     * @param RequisitionListItemInterface $item
     * @return $this
     */
    public function setItem(RequisitionListItemInterface $item)
    {
        $this->item = $item;
        return $this;
    }

    /**
     * Get url of product image from requisition list item.
     *
     * @return string|null
     */
    public function getImageUrl()
    {
        try {
            $product = $this->getProductFromItem($this->getItem());
            $imageUrl = $this->imageHelper->getDefaultPlaceholderUrl('thumbnail');
            if ($product !== null) {
                $imageUrl = $this->imageHelper->init($product, 'product_thumbnail_image')->getUrl();
            }
            return $imageUrl;
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            return null;
        }
    }

    /**
     * @return \Magento\RequisitionList\Api\Data\RequisitionListInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getRequisitionList()
    {
        return $this->requisitionListRepository->get($this->request->getParam('requisition_id'));
    }

    /**
     * Get product from requisition list item.
     *
     * @return ProductInterface|null
     */
    public function getRequisitionListProduct()
    {
        return $this->getProductFromItem($this->getItem());
    }

    /**
     * Check if product options were updated and product should be reconfigured.
     *
     * @return bool
     */
    public function isOptionsUpdated()
    {
        return !empty($this->itemErrors['options_updated']);
    }

    /**
     * Get count of items with errors.
     *
     * @return int
     */
    public function getItemErrorCount()
    {
        return $this->itemErrorCount;
    }

    /**
     * Get list of items that are included in requisition list.
     *
     * @return RequisitionListItemInterface|null
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getRequisitionListItems()
    {
        $requisitionId = $this->getRequest()->getParam('requisition_id');
        if ($requisitionId === null) {
            return null;
        }

        $items = $this->itemSelector->selectAllItemsFromRequisitionList(
            $requisitionId,
            $this->storeManager->getWebsite()->getId()
        );
        foreach ($items as $item) {
            $this->checkForItemError($item);
        }
        uasort($items, function (RequisitionListItemInterface $firstItem, RequisitionListItemInterface $secondItem) {
            $isFirstItemError = !empty($this->errorsByItemId[$firstItem->getId()]);
            $isSecondItemError = !empty($this->errorsByItemId[$secondItem->getId()]);

            return (int)$isSecondItemError - (int)$isFirstItemError;
        });

        return $items;
    }

    /**
     * Check if product is enabled and its quantity is available.
     *
     * @param RequisitionListItemInterface $item
     * @return bool
     */
    private function checkForItemError(RequisitionListItemInterface $item)
    {
        try {
            $errors = $this->validation->validate($item);

            if (count($errors)) {
                $this->errorsByItemId[$item->getId()] = $errors;
                $this->itemErrorCount++;
            }
            $isItemHasErrors = (count($errors) > 0);
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            $this->itemErrorCount++;
            $this->errorsByItemId[$item->getId()] = [__('The SKU was not found in the catalog.')];
            $isItemHasErrors = true;
        }

        return $isItemHasErrors;
    }

    /**
     * Get errors for requisition list item.
     *
     * @param RequisitionListItemInterface $item
     * @return array
     */
    public function getItemErrors(RequisitionListItemInterface $item)
    {
        return !empty($this->errorsByItemId[$item->getId()]) ? $this->errorsByItemId[$item->getId()] : [];
    }

    public function getProductFromItem(RequisitionListItemInterface $item)
    {
        return $this->itemResolver->getFinalProduct($this->itemOptionsLocator->getOptions($item));
    }

    public function pdfImage($imageUrl)
    {
        $mediaIdx = strpos($imageUrl, 'media/');
        if ($mediaIdx === false) {
            return $imageUrl;
        }
        return substr($imageUrl, $mediaIdx);
    }

    /**
     * Check if we should display in catalog prices including and excluding tax.
     *
     * @return bool
     */
    public function displayBothPrices()
    {
        return $this->taxHelper->displayBothPrices($this->_storeManager->getStore());
    }
}
