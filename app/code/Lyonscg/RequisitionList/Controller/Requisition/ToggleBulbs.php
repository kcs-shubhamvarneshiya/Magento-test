<?php

namespace Lyonscg\RequisitionList\Controller\Requisition;

use Capgemini\LightBulbs\Helper\Data as BulbHelper;
use Lyonscg\RequisitionList\Block\Details;
use Lyonscg\RequisitionList\Helper\Item as RequisitionListItemHelper;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface;
use Magento\RequisitionList\Api\Data\RequisitionListInterface;
use Magento\RequisitionList\Api\RequisitionListManagementInterface;
use Magento\RequisitionList\Api\RequisitionListRepositoryInterface;
use Magento\RequisitionList\Model\RequisitionList\Items;
use Magento\RequisitionList\Model\RequisitionListItem\Locator;
use Magento\RequisitionList\Model\RequisitionListItem\Options\Builder;

class ToggleBulbs implements ActionInterface
{
    const ACTIONS_ON_BULBS = [
        Details::BULBS_STATE_HAS_NO_BUT_CAN_HAVE => 'addAction',
        Details::BULBS_STATE_HAS                 => 'removeAction'
    ];

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    private $_request;

    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory
     */
    private $resultRedirectFactory;

    /**
     * @var \Magento\Framework\App\Response\RedirectInterface
     */
    private $_redirect;

    /**
     * @var RequisitionListRepositoryInterface
     */
    private $requisitionListRepository;

    /**
     * @var BulbHelper
     */
    private $bulbHelper;
    /**
     * @var Items
     */
    private $requisitionListItemRepository;

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var Builder
     */
    private $optionsBuilder;

    /**
     * @var Locator
     */
    private $requisitionListItemLocator;

    /**
     * @var RequisitionListManagementInterface
     */
    private $requisitionListManagement;

    /**
     * @var RequisitionListItemHelper
     */
    private $requisitionListitemHelper;

    public function __construct(
        Context $context,
        RequisitionListRepositoryInterface $requisitionListRepository,
        Items $requisitionListItemRepository,
        ProductRepositoryInterface $productRepository,
        Builder $optionsBuilder,
        Locator $requisitionListItemLocator,
        RequisitionListManagementInterface $requisitionListManagement,
        BulbHelper $bulbHelper,
        RequisitionListItemHelper $requisitionListitemHelper
    ) {
        $this->_request = $context->getRequest();
        $this->resultRedirectFactory = $context->getResultRedirectFactory();
        $this->_redirect = $context->getRedirect();
        $this->messageManager = $context->getMessageManager();
        $this->requisitionListRepository = $requisitionListRepository;
        $this->bulbHelper = $bulbHelper;
        $this->requisitionListItemRepository = $requisitionListItemRepository;
        $this->productRepository = $productRepository;
        $this->optionsBuilder = $optionsBuilder;
        $this->requisitionListItemLocator = $requisitionListItemLocator;
        $this->requisitionListManagement = $requisitionListManagement;
        $this->requisitionListitemHelper = $requisitionListitemHelper;
    }

    public function execute()
    {
        $bulbsState = $this->_request->getParam('bulbsState');
        $bulbsAction = self::ACTIONS_ON_BULBS[$bulbsState] ?? false;

        if ($bulbsAction) {
            call_user_func([$this, $bulbsAction]);
        }

        return $this->resultRedirectFactory->create()->setUrl($this->_redirect->getRedirectUrl());
    }

    private function addAction()
    {
        $requisitionId = $this->_request->getParam('requisition_id');

        if (!$requisitionList = $this->getRequisitionList($requisitionId)) {

            return;
        }

        try {
            $items = $requisitionList->getItems();

            if ($this->bulbHelper->isListHasBulb($items, RequisitionListInterface::class)) {

                return;
            }

            foreach ($items as $item) {
                $itemProduct = $this->requisitionListitemHelper->getItemSimpleProduct($item);
                $bulbSku = $itemProduct ? (string) $itemProduct->getData('bulb_sku') : '';
                try {
                    $bulbId = $this->productRepository->get($bulbSku)->getId();
                } catch (NoSuchEntityException $exception) {

                    continue;
                }
                $bulbQty = (int) $itemProduct->getData('bulb_qty');
                $bulbQtyTotal = $bulbQty * $item->getQty();
                $bulbOptions = [];
                $bulbOptions['id'] = $bulbId;
                $bulbOptions['selected_configurable_option'] = '';
                $bulbOptions['related_product'] = '';
                $bulbOptions['item'] = $bulbId;
                $bulbOptions['form_key'] = $this->_request->getParam('form_key');
                $bulbOptions['qty'] = $bulbQtyTotal;
                $bulbOptions = $this->optionsBuilder->build($bulbOptions, 0, false);
                $bulbItem = $this->requisitionListItemLocator->getItem(0);
                $bulbItem->setQty($bulbQtyTotal);
                $bulbItem->setOptions($bulbOptions);
                $bulbItem->setSku($bulbSku);
                $requisitionList->setData('__no_sync', true);
                $this->requisitionListManagement->addItemToList($requisitionList, $bulbItem);
            }
        } catch (LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addError(__('Something went wrong.'));
        }
    }

    /**
     * @return void
     */
    private function removeAction()
    {
        $requisitionId = $this->_request->getParam('requisition_id');

        if (!$requisitionList = $this->getRequisitionList($requisitionId)) {

            return;
        }

        try {
            $items = $requisitionList->getItems();
            foreach ($items as $item) {
                $itemProduct = $this->requisitionListitemHelper->getItemSimpleProduct($item);
                $itemProductSku = $itemProduct ? $itemProduct->getSku() : '';
                if ($this->bulbHelper->isBulb($itemProductSku)) {
                    $this->requisitionListItemRepository->delete($item);
                }
            }
            $requisitionList->setData('__no_sync', true);
            $this->requisitionListRepository->save($requisitionList);
        } catch (LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addError(__('Something went wrong.'));
        }
    }

    /**
     * @param int|string $requisitionId
     * @return RequisitionListInterface|null
     */
    private function getRequisitionList($requisitionId)
    {
        try {
            return $this->requisitionListRepository->get($requisitionId);
        } catch (NoSuchEntityException $exception) {

            return null;
        }
    }
}
