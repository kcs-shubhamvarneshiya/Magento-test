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
 * Error CMS block install script
 */
class ErrorCms implements DataPatchInterface
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
        $block->setIdentifier('partners_insight_error');
        $block->setTitle('Partners Insight Error');
        $block->setContent('<p>An error occurred during a connection to the Partners Insight service. Please try again later.</p>');
        $block->setStores([0]);
        $this->blockRepository->save($block);
    }
}
