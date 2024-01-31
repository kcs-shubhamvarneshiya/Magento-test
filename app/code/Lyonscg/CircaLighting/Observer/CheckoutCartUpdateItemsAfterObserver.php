<?php

namespace Lyonscg\CircaLighting\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Message\ManagerInterface;

class CheckoutCartUpdateItemsAfterObserver implements ObserverInterface
{
    /**
     * @var ManagerInterface
     */
    private $messageManager;

    /**
     * CheckoutCartUpdateItemsAfterObserver constructor.
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        ManagerInterface $messageManager
    ) {
        $this->messageManager = $messageManager;
    }
    protected $_fields = [
        'sidemark',
        'comments_line_item',
    ];

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        try {
            $changed = false;
            /** @var \Magento\Framework\DataObject $dataObject */
            $dataObject = $observer->getEvent()->getInfo();
            $data = $dataObject->getData();
            /** @var \Magento\Checkout\Model\Cart $cart */
            $cart = $observer->getEvent()->getCart();

            $quote = $cart->getQuote();

            foreach ($data as $itemId => $itemInfo) {
                $item = $quote->getItemById($itemId);
                if (!$item) {
                    continue;
                }
                foreach ($this->_fields as $field) {
                    if (isset($itemInfo[$field])) {
                        if ($item->getData($field) != $itemInfo[$field]) {
                            $changed = true;
                        }
                        $item->setData($field, $itemInfo[$field]);
                    }
                }
            }
            if ($changed) {
                $this->messageManager->addSuccessMessage(__('Your changes has been successfully saved.'));
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Something went wrong. Please try again later'));
        }
    }
}
