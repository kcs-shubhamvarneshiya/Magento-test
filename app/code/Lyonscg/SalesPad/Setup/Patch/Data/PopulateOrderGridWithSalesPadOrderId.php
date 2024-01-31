<?php

namespace Lyonscg\SalesPad\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;

class PopulateOrderGridWithSalesPadOrderId implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;
    /**
     * @var OrderCollectionFactory
     */
    private $orderCollectionFactory;

    /**
     * PopulateOrderGridWithSalesPadOrderId constructor.
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param OrderCollectionFactory $orderCollectionFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        OrderCollectionFactory $orderCollectionFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->orderCollectionFactory = $orderCollectionFactory;
    }

    /**
     * @return DataPatchInterface|void
     */
    public function apply()
    {
        $orders = $this->orderCollectionFactory->create()->load();
        foreach ($orders as $order) {
            $order->save();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [];
    }
}
