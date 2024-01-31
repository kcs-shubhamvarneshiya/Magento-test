<?php

namespace Rysun\DataTransfer\Cron;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Rysun\DataTransfer\CustomModuleContext;

class AttributeImpCron
{
    protected $setup;
    protected $context;

    public function __construct(
        ModuleDataSetupInterface $setup,
        CustomModuleContext      $context
    )
    {
        $this->setup = $setup;
        $this->context = $context;
    }

    public function execute()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $attributeInstallDataClass = $objectManager->create(\Rysun\DataTransfer\Setup\InstallData::class);
        $attributeInstallDataClass->install($this->setup, $this->context, "1");
    }

}
