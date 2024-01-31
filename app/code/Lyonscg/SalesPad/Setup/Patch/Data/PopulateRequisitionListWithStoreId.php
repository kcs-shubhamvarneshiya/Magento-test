<?php


namespace Lyonscg\SalesPad\Setup\Patch\Data;

use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory as CustomerCollectionFactory;
use Magento\RequisitionList\Model\ResourceModel\RequisitionList\CollectionFactory as RequisitionListCollectionFactory;
use Lyonscg\SalesPad\Helper\Data as GeneralHelper;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class PopulateRequisitionListWithStoreId implements DataPatchInterface
{
    /**
     * @var CustomerCollectionFactory
     */
    private $customerCollectionFactory;

    /**
     * @var RequisitionListCollectionFactory
     */
    private $requisitionListCollectionFactory;

    public function __construct(
        CustomerCollectionFactory $customerCollectionFactory,
        RequisitionListCollectionFactory $requisitionListCollectionFactory
    ) {

        $this->customerCollectionFactory = $customerCollectionFactory;
        $this->requisitionListCollectionFactory = $requisitionListCollectionFactory;
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getAliases(): array
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function apply()
    {
        $customerCollection = $this->customerCollectionFactory->create();
        $pairs = GeneralHelper::fetchPairsFromCollection(
            $customerCollection,
            ['entity_id', 'store_id']
        );
        $requisitionListCollection = $this->requisitionListCollectionFactory->create();
        foreach ($requisitionListCollection as $item) {
            $customerId = $item->getData('customer_id');

            if ($customerId && isset($pairs[$customerId])) {
                $item->setData('store_id', $pairs[$customerId])->save();
            }
        }


        return $this;
    }
}
