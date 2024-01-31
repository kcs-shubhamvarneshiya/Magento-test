<?php

namespace Lyonscg\CircaLighting\Block;

use Amasty\Orderattr\Model\Attribute\Frontend\CollectionProvider;
use Amasty\Orderattr\Model\Attribute\InputType\InputTypeProvider;
use Magento\Checkout\Model\Session;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class OrderAttributes extends Template
{
    protected $attributeCodes = [
        'project_name',
        'po_number',
        'comments'
    ];

    /**
     * @var CollectionProvider
     */
    protected $collectionProvider;

    /**
     * @var InputTypeProvider
     */
    protected $inputTypeProvider;

    /**
     * @var \Amasty\Orderattr\Api\Data\CheckoutAttributeInterface[]
     */
    protected $attributes = [];

    /**
     * @var Session
     */
    protected $checkoutSession;

    /**
     * OrderAttributes constructor.
     * @param Context $context
     * @param CollectionProvider $collectionProvider
     * @param InputTypeProvider $inputTypeProvider
     * @param Session $checkoutSession
     * @param array $data
     */
    public function __construct(
        Context $context,
        CollectionProvider $collectionProvider,
        InputTypeProvider $inputTypeProvider,
        Session $checkoutSession,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->collectionProvider = $collectionProvider;
        $this->inputTypeProvider = $inputTypeProvider;
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * @return \Amasty\Orderattr\Api\Data\CheckoutAttributeInterface[]
     */
    public function getAttributes()
    {
        if (empty($this->attributes)) {
            foreach ($this->collectionProvider->getAttributes() as $attribute) {
                if (in_array($attribute->getAttributeCode(), $this->attributeCodes)) {
                    $this->attributes[] = $attribute;
                }
            }
        }
        return $this->attributes;
    }

    /**
     * @return \Magento\Quote\Api\Data\CartInterface|\Magento\Quote\Model\Quote
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getQuote()
    {
        return $this->checkoutSession->getQuote();
    }

    protected function _getEmptyAttributeValues()
    {
        $values = [];
        foreach ($this->getAttributes() as $attribute) {
            $values[$attribute->getAttributeCode()] = '';
        }
        return $values;
    }

    /**
     * @return string
     */
    public function getOrderAttributeData()
    {
        try {
            $quote = $this->getQuote();
        } catch (\Exception $e) {
            $quote = false;
        }

        if (!$quote || !$quote->getId()) {
            return json_encode($this->_getEmptyAttributeValues());
        }

        $extensionAttributes = $quote->getExtensionAttributes();
        if (!$extensionAttributes || empty($extensionAttributes)) {
            return json_encode($this->_getEmptyAttributeValues());
        }

        $orderAttributes = $extensionAttributes->getAmastyOrderAttributes();
        $orderAttributesData = $this->_getEmptyAttributeValues();
        if (is_array($orderAttributes)) {
            foreach ($orderAttributes as $orderAttribute) {
                $orderAttributesData[$orderAttribute->getAttributeCode()] = $orderAttribute->getValue();
            }
        }

        return json_encode($orderAttributesData);

    }

    /**
     * @return string
     */
    public function getJsLayout()
    {
        $attributes = $this->getAttributes();
        if (empty($attributes)) {
            return parent::getJsLayout();
        }

        try {
            $jsLayout = $this->jsLayout;

            $attributesConfig = $this->inputTypeProvider->getFrontendElements($attributes, 'orderAttributesProvider', 'orderAttributes');

            $jsLayout['components']['orderAttributes']['children']['order-attributes-fieldset']['children'] = $attributesConfig;

            return json_encode($jsLayout);
        } catch (\Exception $e) {
            $this->_logger->error($e);
            return parent::getJsLayout();
        }
    }
}
