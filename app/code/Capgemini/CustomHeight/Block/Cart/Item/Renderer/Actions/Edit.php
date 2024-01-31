<?php

namespace Capgemini\CustomHeight\Block\Cart\Item\Renderer\Actions;

class Edit extends \Magento\Checkout\Block\Cart\Item\Renderer\Actions\Edit
{
    /**
     * Get item configure url
     *
     * @return string
     */
    public function getConfigureUrl()
    {
        try {
            if (!empty($this->getItem()->getExtensionAttributes()->getCustomHeightValue())) {
                return $this->getUrl(
                    'checkout/cart/configure',
                    [
                        'id' => $this->getItem()->getId(),
                        'product_id' => $this->getItem()->getProduct()->getId(),
                        'custom_height_value' => $this->getItem()->getExtensionAttributes()->getCustomHeightValue()
                    ]
                );
            }
        } catch (\Exception $e) {}
        return parent::getConfigureUrl();
    }
}
