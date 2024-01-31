<?php

namespace Lyonscg\CircaLighting\Block;

class Docupdate extends \Magento\Framework\View\Element\Template
{
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    public function getCartUrl()
    {
        return $this->getUrl('circalighting/docupdate/cart');
    }

    public function getQuoteUrl()
    {
        return $this->getUrl('circalighting/docupdate/quote');
    }
}
