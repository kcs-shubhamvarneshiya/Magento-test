<?php


namespace Lyonscg\SalesPad\Setup\Patch\Data;

use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory as CustomerCollectionFactory;
use Lyonscg\SalesPad\Model\ResourceModel\Sync\Customer\CollectionFactory as CustomerSyncCollectionFactory;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;
use Lyonscg\SalesPad\Model\ResourceModel\Sync\Order\CollectionFactory as OrderSyncCollectionFactory;
use Magento\RequisitionList\Model\ResourceModel\RequisitionList\CollectionFactory as QuoteCollectionFactory;
use Lyonscg\SalesPad\Model\ResourceModel\Sync\Quote\CollectionFactory as QuoteSyncCollectionFactory;
use Lyonscg\SalesPad\Helper\Data as GeneralHelper;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class PopulateSalesPadSyncEntitiesWithStoreIds implements DataPatchInterface
{
    /**
     * Structured data for each sync entity
     * purposed for efficient code.
     *
     * @var array[]
     */
    private $entitiesData = [
        'customer' => [
            'factory'              => null,
            'sync_factory'         => null
        ],
        'order' => [
            'factory'              => null,
            'sync_factory'         => null
        ],
        'quote' => [
            'factory'              => null,
            'sync_factory'         => null
        ],
    ];

    public function __construct(
        CustomerCollectionFactory $customerCollectionFactory,
        CustomerSyncCollectionFactory $customerSyncCollectionFactory,
        OrderCollectionFactory $orderCollectionFactory,
        OrderSyncCollectionFactory $orderSyncCollectionFactory,
        QuoteCollectionFactory $quoteCollectionFactory,
        QuoteSyncCollectionFactory $quoteSyncCollectionFactory
    ) {
        $this->entitiesData['customer']['factory'] = $customerCollectionFactory;
        $this->entitiesData['customer']['sync_factory'] = $customerSyncCollectionFactory;
        $this->entitiesData['order']['factory'] = $orderCollectionFactory;
        $this->entitiesData['order']['sync_factory'] = $orderSyncCollectionFactory;
        $this->entitiesData['quote']['factory'] = $quoteCollectionFactory;
        $this->entitiesData['quote']['sync_factory'] = $quoteSyncCollectionFactory;
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies(): array
    {
        return [PopulateRequisitionListWithStoreId::class];
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
        foreach ($this->entitiesData as $entity => $data) {
            $collection = $data['factory']->create();
            $pairs = GeneralHelper::fetchPairsFromCollection(
                $collection,
                ['entity_id', 'store_id']
            );
            $syncCollection = $data['sync_factory']->create();
            $syncEntityFieldName = $entity . '_id';
            foreach ($syncCollection as $item) {
                $storeId = $pairs[$item->getData($syncEntityFieldName)] ?? null;

                if (!$storeId) {

                    continue;
                }

                $item->setData('store_id', $storeId);
                $item->save();
            }
        }

        return $this;
    }
}
