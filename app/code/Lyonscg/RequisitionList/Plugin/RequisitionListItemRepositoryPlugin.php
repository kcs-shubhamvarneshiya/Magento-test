<?php

namespace Lyonscg\RequisitionList\Plugin;

use Magento\Framework\Api\SearchResultsInterface;
use Magento\RequisitionList\Api\Data\RequisitionListItemExtensionFactory;
use Magento\RequisitionList\Api\Data\RequisitionListItemInterface;
use Magento\RequisitionList\Model\RequisitionList\Items;
use Magento\Framework\Message\ManagerInterface;

class RequisitionListItemRepositoryPlugin
{
    protected $request;

    /**
     * @var RequisitionListItemExtensionFactory
     */
    protected $itemExtensionFactory;

    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * RequisitionListItemRepositoryPlugin constructor.
     * @param RequisitionListItemExtensionFactory $itemExtensionFactory
     */
    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        RequisitionListItemExtensionFactory $itemExtensionFactory,
        ManagerInterface $messageManager
    ) {
        $this->request = $request;
        $this->itemExtensionFactory = $itemExtensionFactory;
        $this->messageManager = $messageManager;
    }

    /**
     * @param Items $subject
     * @param RequisitionListItemInterface $result
     * @return RequisitionListItemInterface
     */
    public function afterGet(Items $subject, RequisitionListItemInterface $result)
    {
        return $this->_loadItemData($result);
    }

    /**
     * @param Items $subject
     * @param SearchResultsInterface $results
     * @return SearchResultsInterface
     */
    public function afterGetList(Items $subject, SearchResultsInterface $results)
    {
        foreach ($results->getItems() as $item) {
            $this->_loadItemData($item);
        }
        return $results;
    }

    /**
     * @param RequisitionListItemInterface $item
     * @return RequisitionListItemInterface
     */
    protected function _loadItemData(RequisitionListItemInterface $item)
    {
        $extensionAttributes = $item->getExtensionAttributes() ?? $this->itemExtensionFactory->create();
        $extensionAttributes->setSidemark($item->getSidemark());
        $extensionAttributes->setCommentsLineItem($item->getCommentsLineItem());
        $item->setExtensionAttributes($extensionAttributes);
        return $item;
    }

    public function beforeSave(Items $subject, RequisitionListItemInterface $item)
    {
        $extensionAttributes = $item->getExtensionAttributes() ?? $this->itemExtensionFactory->create();
        //$changed = false;
        if (($sidemark = $this->_getItemPostData($item->getId(), 'sidemark')) !== false) {
            $item->setSidemark($sidemark);
            if ($extensionAttributes->getSidemark() != $sidemark) {
                $changed = true;
            }
            $extensionAttributes->setSidemark($sidemark);
        }
        if (($commentsLineItem = $this->_getItemPostData($item->getId(), 'comments_line_item')) !== false) {
            $item->setCommentsLineItem($commentsLineItem);
            if ($extensionAttributes->getCommentsLineItem() != $commentsLineItem) {
                $changed = true;
            }
            $extensionAttributes->setCommentsLineItem($commentsLineItem);
        }
        $item->setExtensionAttributes($extensionAttributes);
       /* if ($changed) {
            $this->messageManager->addSuccessMessage(__('Your changes has been successfully saved.'));
        }*/
        return [$item];
    }

    protected function _getItemPostData($itemId, $field)
    {
        $cart = $this->request->getParam('cart', false);
        if (!is_array($cart)) {
            return false;
        }
        if (!isset($cart[$itemId])) {
            return false;
        }
        return $cart[$itemId][$field] ?? false;
    }
}
