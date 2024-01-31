<?php
/**
 * Capgemini_PartnersInsight
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\PartnersInsight\Setup\Patch\Data;

use Magento\Cms\Model\BlockFactory;
use Magento\Cms\Model\BlockRepository;
use Magento\Framework\Setup\Patch\DataPatchInterface;

/**
 * Dashboard CMS block install script
 */
class DashboardCms implements DataPatchInterface
{
    /**
     * @var BlockFactory
     */
    protected $blockFactory;
    /**
     * @var BlockRepository
     */
    protected $blockRepository;

    /**
     * Constructor.
     *
     * @param BlockFactory $blockFactory
     */
    public function __construct(
        BlockFactory $blockFactory,
        BlockRepository $blockRepository
    ) {
        $this->blockFactory    = $blockFactory;
        $this->blockRepository = $blockRepository;
    }

    /**
     * {@inheritDoc}
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function apply()
    {
        $block = $this->blockFactory->create();
        $block->setIdentifier(\Capgemini\PartnersInsight\Block\Customer\Account\DashboardCms::CMS_BLOCK_IDENTIFIER);
        $block->setTitle('Partners Insight Dashboard');
        $block->setContent('<div class="block-title">
    <strong>Partners Insight</strong>
</div>
<div class="block-content">
<p>
    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque nec neque tincidubt,
    hendererit augue vel, scelerisque diam. Sed eros sapien, vulputate et ex in, volutpat
    fermetum justo. Donec ut lobortis orci, sed congue magna. Vivanus quis neque
    condimentum, dignissim sem sit amet, aliquet arcu. Sed ac lorem alicuet, sagittis ex sed,
    commodo nulla. Praesent et colutpat arcu. Quisque ultrices fermentum porttitor. Integer
    sit amet uliamcorper lacus. 
</p>
</div>');
        $block->setStores([0]);
        $this->blockRepository->save($block);
    }
}
