<?php

namespace Lyonscg\Wishlist\Plugin;

use Magento\Framework\App\Response\RedirectInterface;

class AddPlugin
{
    protected $_redirect;
    public function __construct(
        \Magento\Framework\App\Response\RedirectInterface $redirect
    ) {
        $this->_redirect = $redirect;
    }

    public function afterExecute(\Magento\Wishlist\Controller\Index\Add $subject, $proceed)
    {
        $returnUrl = $this->_redirect->getRefererUrl();

        $proceed->setUrl($returnUrl);

        return $proceed;
    }
}
