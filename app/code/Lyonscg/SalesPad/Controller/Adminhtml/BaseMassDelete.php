<?php


namespace Lyonscg\SalesPad\Controller\Adminhtml;

use Lyonscg\SalesPad\Model\ResourceModel\Api\ErrorLog\CollectionFactory as ErrorLogCollectionFactory;
use Lyonscg\SalesPad\Model\ResourceModel\Sync\Customer\CollectionFactory as CustomerSyncCollectionFactory;
use Lyonscg\SalesPad\Model\ResourceModel\Sync\Order\CollectionFactory as OrderSyncCollectionFactory;
use Lyonscg\SalesPad\Model\ResourceModel\Sync\Quote\CollectionFactory as QuoteSyncCollectionFactory;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\ObjectManagerInterface;
use Magento\Ui\Component\MassAction\Filter;

class BaseMassDelete extends Action
{
    /**
     * @var Filter
     */
    private $filter;

    /**
     * @var ErrorLogCollectionFactory|CustomerSyncCollectionFactory|OrderSyncCollectionFactory|QuoteSyncCollectionFactory
     */
    protected $collectionFactory;

    /**
     * @param Context $context
     * @param Filter $filter
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(Context $context, Filter $filter, ObjectManagerInterface $objectManager)
    {
        parent::__construct($context);

        $this->filter = $filter;
        $this->collectionFactory = $objectManager->get(static::COLLECTION_FACTORY_CLASS);
    }
    /**
     * Execute action
     *
     * @return Redirect
     * @throws LocalizedException|\Exception
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = $collection->getSize();
        foreach ($collection as $item) {
            $item->delete();
        }

        $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $collectionSize));

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
