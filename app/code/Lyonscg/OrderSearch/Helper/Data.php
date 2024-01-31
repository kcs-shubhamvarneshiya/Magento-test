<?php

namespace Lyonscg\OrderSearch\Helper;

use Magento\Framework\Registry as CoreRegistry;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $coreRegistry;

    public function __construct(
        CoreRegistry $coreRegistry
    ) {
        $this->coreRegistry = $coreRegistry;
    }

    public function getOrderData()
    {
        $data = $this->coreRegistry->registry('salespad_current_order');
        return $data ?? [];
    }
}
