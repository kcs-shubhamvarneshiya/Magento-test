<?php
namespace Lyonscg\CircaLighting\Block;
class StockStatus extends \Magento\Framework\View\Element\Template
{
    protected $_stockItemRepository;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockItemRepository,
        array $data = []
    )
    {
        $this->_stockItemRepository = $stockItemRepository;
        parent::__construct($context, $data);
    }

    /**
     * @param $product
     * @return string
     */
    public function getStockItem($product)
    {
        $stockItem = $product->getExtensionAttributes()->getStockItem();
        if ($stockItem->getIsInStock()) {
            $returnStatus = 'In Stock';
        } else {
            $returnStatus = 'Out of Stock';
        }

        return $returnStatus;
    }
}
