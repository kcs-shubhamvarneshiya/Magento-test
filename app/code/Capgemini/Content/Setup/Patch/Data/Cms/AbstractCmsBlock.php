<?php
/**
 * Capgemini_Content
 */

namespace Capgemini\Content\Setup\Patch\Data\Cms;

/**
 * Class AbstractCmsBlock
 */
abstract class AbstractCmsBlock extends AbstractCms
{

    const VC_STORE_CODE = 'default';

    /**
     * @param array $blockData
     */
    protected function upsertBlock(array $blockData): void
    {
        $block = $this->getCmsBlockByIdentifier($blockData['identifier']);
        /**
         * Create the block if it does not exists, otherwise update the content
         */
        if (!$block->getId()) {
            $block->setData($blockData);
        } else {
            $block->addData($blockData);
        }

        try {
            $block->save();
        } catch (\Exception $e) {
        }
    }

    /**
     * @param string $identifier
     */
    protected function revertBlock($identifier)
    {
        $block = $this->blockFactory
            ->create()
            ->load($identifier, 'identifier');

        if ($block->getId()) {
            try {
                $block->delete();
            } catch (\Exception $e) {
            }
        }
    }
}
