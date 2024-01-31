<?php
/**
 * Lyonscg_RequisitionList
 *
 * @category  Lyons
 * @package   Lyonscg_RequisitionList
 * @author    Tetiana Mamchik<tanya.mamchik@capgemini.com>
 * @copyright Copyright (c) 2020 Lyons Consulting Group (www.lyonscg.com)
 */
namespace Lyonscg\RequisitionList\ViewModel;

use Lyonscg\RequisitionList\Block\Item as BlockRequisitionItem;
use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\RequisitionList\Api\Data\RequisitionListInterface;
use Magento\Framework\Locale\FormatInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;

/**
 * Class ExtraData
 * @package Lyonscg\RequisitionList\ViewModel
 */
class RequisitionListTotal extends DataObject implements ArgumentInterface
{
    /**
     * @var null|float
     */
    private $requisitionListSubtotal;

    /**
     * @var null|float
     */
    private $requisitionListRetailSubtotal;

    /**
     * @var null|float
     */
    private $requisitionListTotal;

    /**
     * @var null|float
     */
    private $requisitionListRetailTotal;

    /**
     * @var null|float
     */
    private $estSalesTax;

    /**
     * @var null|float
     */
    private $estRetailSalesTax;

    /**
     * @var BlockRequisitionItem
     */
    private $blockRequisitionItem;

    /**
     * @var RequisitionListInterface
     */
    private $requisitionList;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var FormatInterface
     */
    private $localeFormat;

    /** @var int|float */
    private $taxRate = 0;

    /** @var int|float */
    private $markup = 0;

    /** @var null|int */
    private $requisitionListItemsTotal;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime 
     */
    private $dateTime;

    /**
     * RequisitionListTotal constructor.
     * @param BlockRequisitionItem $blockRequisitionItem
     * @param FormatInterface $localeFormat
     * @param SerializerInterface $serializer
     * @param DateTime $dateTime
     * @param array $data
     */
    public function __construct(
        BlockRequisitionItem $blockRequisitionItem,
        FormatInterface $localeFormat,
        SerializerInterface $serializer,
        DateTime $dateTime,
        array $data = []
    ) {
        parent::__construct($data);
        $this->blockRequisitionItem = $blockRequisitionItem;
        $this->serializer = $serializer;
        $this->localeFormat = $localeFormat;
        $this->dateTime = $dateTime;
    }

    /**
     * @param RequisitionListInterface $requisitionList
     * @return $this
     */
    public function setRequisitionList($requisitionList)
    {
        $this->requisitionList = $requisitionList;
        return $this;
    }

    /**
     * @param float|int $taxRate
     * @return $this
     */
    public function setTaxRate($taxRate)
    {
        $this->taxRate = $taxRate;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCurrentDate()
    {
        return $this->dateTime->gmtDate();
    }

    /**
     * @param bool $formatted
     * @return float|int|null
     */
    public function getRequisitionListSubtotal($formatted = false)
    {
        if (!$this->requisitionListSubtotal) {
            $subtotal = 0;
            foreach ($this->requisitionList->getItems() as $item) {
                $this->blockRequisitionItem->setItem($item);
                $subtotal = $subtotal + $this->blockRequisitionItem->getFinalPrice() * $item->getQty();
            }
            $this->requisitionListSubtotal = $subtotal;
        }
        return ($formatted) ? $this->blockRequisitionItem->formatProductPrice($this->requisitionListSubtotal) :
            $this->requisitionListSubtotal;
    }

    /**
     * @return float|int|mixed|null
     */
    public function getRequisitionListItemsTotal()
    {
        if (!$this->requisitionListItemsTotal) {
            $itemsTotal = 0;
            foreach ($this->requisitionList->getItems() as $item) {
                $this->blockRequisitionItem->setItem($item);
                $itemsTotal = $itemsTotal + $item->getQty();
            }
            $this->requisitionListItemsTotal = $itemsTotal;
        }
        return $this->requisitionListItemsTotal;
    }

    /**
     * @param bool $formatted
     * @return float|int|null
     */
    public function getRequisitionListRetailSubtotal($formatted = false)
    {
        if (!$this->requisitionListRetailSubtotal) {
            $subtotal = 0;
            foreach ($this->requisitionList->getItems() as $item) {
                $this->blockRequisitionItem->setItem($item);
                $product = $this->blockRequisitionItem->getRequisitionListProduct();
                if ($product) {
                    $subtotal += ($product->getPrice() * $item->getQty());
                }
            }
            $this->requisitionListRetailSubtotal = $subtotal;
        }
        return ($formatted) ? $this->blockRequisitionItem->formatProductPrice($this->requisitionListRetailSubtotal) :
            $this->requisitionListRetailSubtotal;
    }

    /**
     * @param bool $formatted
     * @return float|int
     */
    public function getEstSalesTax($formatted = false)
    {
        if (!$this->estSalesTax) {
            $this->estSalesTax = ($this->getRequisitionListSubtotal()) * $this->taxRate / 100;
        }
        return ($formatted) ? $this->blockRequisitionItem->formatProductPrice($this->estSalesTax) :
            $this->estSalesTax;
    }

    /**
     * @param bool $formatted
     * @return float|int
     */
    public function getEstRetailSalesTax($formatted = false)
    {
        if (!$this->estRetailSalesTax) {
            $this->estRetailSalesTax = ($this->getRequisitionListRetailSubtotal()) * $this->taxRate / 100;
        }
        return ($formatted) ? $this->blockRequisitionItem->formatProductPrice($this->estRetailSalesTax) :
            $this->estRetailSalesTax;
    }

    /**
     * @param bool $formatted
     * @return float|int|null
     */
    public function getRequisitionListTotal($formatted = false)
    {
        if (!$this->requisitionListTotal) {
            $this->requisitionListTotal =
                $this->getRequisitionListSubtotal() +
                $this->getEstSalesTax();
        }
        return ($formatted) ? $this->blockRequisitionItem->formatProductPrice($this->requisitionListTotal) :
            $this->requisitionListTotal;
    }

    /**
     * @param bool $formatted
     * @return float|int|null
     */
    public function getRequisitionListRetailTotal($formatted = false)
    {
        if (!$this->requisitionListRetailTotal) {
            $this->requisitionListRetailTotal =
                $this->getRequisitionListRetailSubtotal() +
                $this->getEstRetailSalesTax();
        }
        return ($formatted) ? $this->blockRequisitionItem->formatProductPrice($this->requisitionListRetailTotal) :
            $this->requisitionListRetailTotal;
    }

    /**
     * @return bool|string
     */
    public function getPriceFormat()
    {
        return $this->serializer->serialize($this->localeFormat->getPriceFormat());
    }
}
