<?php

namespace Rysun\DataTransfer;

use Magento\Framework\Setup\ModuleContextInterface;

class CustomModuleContext implements ModuleContextInterface
{
    /**
     * Gets current version of the module
     *
     * @return string
     */
    public function getVersion()
    {
        return "1000.0.0";
    }
}
