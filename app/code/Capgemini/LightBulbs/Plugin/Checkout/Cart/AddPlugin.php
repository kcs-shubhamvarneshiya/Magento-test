<?php


namespace Capgemini\LightBulbs\Plugin\Checkout\Cart;


class AddPlugin
{
    const LAST_ADDED = 'lightbulb_last_added';
    protected $json;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @var \Magento\Framework\View\LayoutInterface
     */
    protected $layout;

    public function __construct(
        \Magento\Framework\Serialize\Serializer\Json $json,
        \Magento\Checkout\Model\Session $session,
        \Magento\Framework\View\LayoutInterface $layout
    ) {
        $this->json = $json;
        $this->checkoutSession = $session;
        $this->layout = $layout;
    }

    public function afterExecute(\Magento\Checkout\Controller\Cart\Add $subject, $result)
    {
        /** @var $response \Magento\Framework\App\Response\Http */
        $response = $subject->getResponse();
        $result = $response->getContent();
        try {
            $result = $this->_addModalHtml($result);
            $response->setContent($result);
        } catch (\Exception $e) {
        }
    }

    protected function _addModalHtml($result)
    {
        $quote = $this->checkoutSession->getQuote();
        if (!$quote || !$quote->getId()) {
            return $result;
        }

        /** @var $lastItem \Magento\Quote\Model\Quote\Item */
        $lastItem = $this->checkoutSession->getData(self::LAST_ADDED);
        $this->checkoutSession->unsetData(self::LAST_ADDED);

        /*
        $lastItem = $quote->getItemById($lastItemId);
        */
        if (!$lastItem || !($lastItem instanceof \Magento\Quote\Model\Quote\Item)) {
            return $result;
        }

        $itemToUse = $lastItem;

        // if the item is a child item, make sure to grab upsells from the parent
        /*if ($lastItem->getParentItem()) {
            $itemToUse = $lastItem->getParentItem();
        }*/

        $modalHtml = $this->_getModalHtml($itemToUse);
        if (empty($modalHtml)) {
            return $result;
        }
        $data = $this->json->unserialize($result);
        $data['lightbulb_upsell_modal'] = $modalHtml;
        return $this->json->serialize($data);
    }

    protected function _getModalHtml(\Magento\Quote\Model\Quote\Item $item)
    {
        if (!$item || !$item->getId()) {
            return '';
        }
        /** @var \Capgemini\LightBulbs\Block\Upsell $block */
        $block = $this->layout
            ->createBlock(\Capgemini\LightBulbs\Block\Upsell::class)
            ->setLastOrderedItem($item);
        return $block->toHtml();
    }
}
