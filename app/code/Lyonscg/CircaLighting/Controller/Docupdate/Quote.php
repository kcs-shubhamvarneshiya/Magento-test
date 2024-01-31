<?php

namespace Lyonscg\CircaLighting\Controller\Docupdate;

use Lyonscg\SalesPad\Model\Sync\Quote\Pull;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\Action;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\RequisitionList\Api\Data\RequisitionListItemInterface;
use Magento\RequisitionList\Api\RequisitionListRepositoryInterface;
use Magento\RequisitionList\Model\RequisitionList\Items;
use Magento\RequisitionList\Model\Action\RequestValidator;
use Magento\RequisitionList\Model\RequisitionListItem\Merger;
use Magento\RequisitionList\Model\RequisitionListItem\Options\Builder;
use Psr\Log\LoggerInterface;
use Lyonscg\RequisitionList\Helper\Item as RequisitionListItemHelper;

class Quote extends Action
{
    /**
     * @var RequestValidator
     */
    private $requestValidator;

    /**
     * @var RequisitionListRepositoryInterface
     */
    private $requisitionListRepository;

    /**
     * @var Items
     */
    private $requisitionListItemRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var StockRegistryInterface
     */
    private $stockRegistry;

    /**
     * @var Json
     */
    private $json;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var Session
     */
    private $customerSession;

    /**
     * @var Merger
     */
    private $itemMerger;

    /**
     * @var Pull
     */
    private $pull;

    /**
     * @var RequisitionListItemHelper
     */
    private $requisitionListItemHelper;

    /**
     * @var
     */
    private $requisitionList;

    /**
     * @var string
     */
    private $html = '';

    /**
     * @param Context $context
     * @param RequestValidator $requestValidator
     * @param RequisitionListRepositoryInterface $requisitionListRepository
     * @param Items $requisitionListItemRepository
     * @param LoggerInterface $logger
     * @param StockRegistryInterface $stockRegistry
     * @param Json $json
     * @param ProductRepositoryInterface $productRepository
     * @param RequisitionListItemHelper $requisitionListItemHelper
     * @param Merger $itemMerger
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        RequestValidator $requestValidator,
        RequisitionListRepositoryInterface $requisitionListRepository,
        Items $requisitionListItemRepository,
        LoggerInterface $logger,
        StockRegistryInterface $stockRegistry,
        Json $json,
        ProductRepositoryInterface $productRepository,
        Session $customerSession,
        Merger $itemMerger,
        Pull $pull,
        RequisitionListItemHelper $requisitionListItemHelper
    ) {
        parent::__construct($context);
        $this->requestValidator = $requestValidator;
        $this->requisitionListRepository = $requisitionListRepository;
        $this->requisitionListItemRepository = $requisitionListItemRepository;
        $this->logger = $logger;
        $this->stockRegistry = $stockRegistry;
        $this->json = $json;
        $this->productRepository = $productRepository;
        $this->itemMerger = $itemMerger;
        $this->pull = $pull;
        $this->requisitionListItemHelper = $requisitionListItemHelper;
        $this->customerSession = $customerSession;
    }

    public function execute()
    {
        $result = $this->requestValidator->getResult($this->getRequest());
        if ($result) {
            return $this->jsonResponse(__('Unable to save changes to item.'));
        }

        try {
            $requisitionId = $this->_request->getParam('requisition_id');
            $this->requisitionList = $this->requisitionListRepository->get($requisitionId);

            $update = $this->_request->getParam('qty');
            $ids = array_keys($update);

            foreach ($this->requisitionList->getItems() as $item) {
                if (in_array($item->getId(), $ids)) {
                    $qty = (float)$update[$item->getId()];
                    if (!$this->isDecimalQtyUsed($item)) {
                        $qty = (int)$qty;
                    }
                    $item->setQty($qty);
                    $this->requisitionListItemRepository->save($item);
                    unset($update[$item->getId()]);
                } else {
                    $this->requisitionListItemRepository->delete($item);
                }
            }

            $fakeBulbs = $this->getRequest()->getParam('fake-bulbs');

            foreach ($update as $id => $qty) {
                $sku = $fakeBulbs[$id] ?? false;
                $bulb = $this->productRepository->get($sku);
                $this->createBulbAndAddToRequisition($bulb, $qty);
            }

            $this->requisitionListRepository->save($this->requisitionList);

            $customer = $this->customerSession->getCustomerData();

            try {
                $syncResult = $this->pull->executeRequisitionListView($customer);
            } catch (\Exception $exception) {
                $this->logger->critical($exception);

                return $this->jsonResponse(__($exception->getMessage()));
            }

            if (!$syncResult) {
                $this->logger->error(sprintf(
                    '\Lyonscg\CircaLighting\Controller\Docupdate\Quote: quote with ID of %s failed to sync',
                    $requisitionId
                ));

                return $this->jsonResponse(__('Unable to sync the quote.'));
            }

            $this->html = $this->_view->loadLayout('requisition_list_requisition_view')
                ->getLayout()
                ->getBlock('requisition.items.grid')
                ->toHtml();

            $this->messageManager->addSuccessMessage(__('Changes to the quote were successfully saved.'));
        } catch (LocalizedException $e) {
            return $this->jsonResponse($e->getMessage());
        } catch (\Exception $e) {
            $this->logger->critical($e);
            return $this->jsonResponse(__('Something went wrong.'));
        }

        return $this->jsonResponse();

    }

    private function jsonResponse(string $error = '')
    {
        return $this->getResponse()->representJson(
            $this->json->serialize($this->getResponseData($error))
        );
    }

    /**
     * Returns response data.
     *
     * @param string $error
     * @return array
     */
    private function getResponseData(string $error = ''): array
    {
        $response = ['success' => true];

        if (!empty($error)) {
            $response = [
                'success' => false,
                'error_message' => $error,
            ];
        } else {
            $response['html'] = $this->html;
        }

        return $response;
    }

    /**
     * Is stock item qty uses decimal
     *
     * @param RequisitionListItemInterface $item
     * @return bool
     * @throws NoSuchEntityException
     */
    private function isDecimalQtyUsed(RequisitionListItemInterface $item): bool
    {
        $stockItem = $this->stockRegistry->getStockItemBySku($item->getSku());

        return $stockItem->getIsQtyDecimal();
    }

    /**
     * @param ProductInterface $bulb
     * @param int|float $qty
     * @return void
     * @throws Builder\ConfigurationException
     * @throws LocalizedException
     */
    private function createBulbAndAddToRequisition(ProductInterface $bulb, $qty)
    {
        $bulbItem = $this->requisitionListItemHelper->createNewStandardItemFromProduct($bulb);
        $bulbItem->setQty($qty);

        $items = $this->requisitionList->getItems();
        $requisitionListItems = $this->itemMerger->mergeItem($items, $bulbItem);
        $this->requisitionList->setItems($requisitionListItems);
    }
}
