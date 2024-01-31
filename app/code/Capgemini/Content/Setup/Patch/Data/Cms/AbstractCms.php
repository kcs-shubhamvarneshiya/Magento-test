<?php
/**
 * Capgemini_Content
 */

namespace Capgemini\Content\Setup\Patch\Data\Cms;

use Magento\Cms\Model\BlockFactory;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Magento\Store\Api\StoreRepositoryInterface;

/**
 * Class AbstractCmsBlock
 */
abstract class AbstractCms implements DataPatchInterface, PatchRevertableInterface
{
    const VC_STORE_CODE  = 'default';

    /**
     * @var StoreRepositoryInterface
     */
    protected $storeRepository;

    /**
     * @var BlockFactory
     */
    protected $blockFactory;

    /**
     * AbstractCms constructor.
     * @param StoreRepositoryInterface $storeRepository
     * @param BlockFactory $blockFactory
     */
    public function __construct(
        StoreRepositoryInterface $storeRepository,
        BlockFactory $blockFactory
    ) {
        $this->storeRepository = $storeRepository;
        $this->blockFactory    = $blockFactory;
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        /**
         * No dependencies for this
         */
        return [];
    }

    /**
     * Delete the block
     */
    public function revert()
    {
        /**
         * Delete the block
         */
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        /**
         * Aliases are useful if we change the name of the patch until then we do not need any
         */
        return [];
    }

    /**
     * Get POD store id by store code
     *
     * @param string $storeCode
     * @return int
     */
    protected function getStoreIdByCode($storeCode)
    {
        try {
            $store = $this->storeRepository->get($storeCode);
            return $store->getId();
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
        }

        return 0;
    }

    /**
     * Get CMS block by string identifier
     *
     * @param string $blockIdentifier
     * @return \Magento\Cms\Model\Block
     */
    public function getCmsBlockByIdentifier($blockIdentifier)
    {
        return $this->blockFactory->create()->load($blockIdentifier, 'identifier');
    }

    /**
     * Get block numeric ID by string identifier
     *
     * @param string $blockIdentifier
     * @return false|int
     */
    public function getCmsBlockIdByIdentifier($blockIdentifier)
    {
        $block = $this->getCmsBlockByIdentifier($blockIdentifier);
        return $block->getId() ?: false;
    }

    /**
     * Get Visual Comfort Store ID
     *
     * @return int
     */
    protected function getVcStoreId()
    {
        return $this->getStoreIdByCode(self::VC_STORE_CODE);
    }
}
