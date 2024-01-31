<?php

namespace Lyonscg\StaticBlock\Setup;

use Magento\Cms\Model\BlockFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;


class InstallData implements InstallDataInterface
{
    private $blockFactory;

    public function __construct(BlockFactory $blockFactory)
    {
        $this->blockFactory = $blockFactory;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $cmsBlock = $this->blockFactory->create()->load('footer_links_block', 'identifier');

        if (!$cmsBlock->getId()) {
            $cmsBlockData = [
                'title' => 'Footer Links Block',
                'identifier' => 'footer_links_block',
                'content' => '',
                'is_active' => 1,
                'stores' => [0],
                'sort_order' => 0
            ];

            $this->blockFactory->create()->setData($cmsBlockData)->save();
        }
    }
}
