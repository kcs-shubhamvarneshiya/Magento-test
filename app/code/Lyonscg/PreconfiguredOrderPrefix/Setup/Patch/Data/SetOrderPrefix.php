<?php
declare(strict_types=1);

namespace Lyonscg\PreconfiguredOrderPrefix\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\SalesSequence\Model\ResourceModel\Meta as ResourceSequenceMeta;
use Magento\SalesSequence\Model\ResourceModel\Profile;

class SetOrderPrefix implements DataPatchInterface
{
    const STORE_PREFIXES = [
        'default' => 'W',
        'uk' => 'WUK',
        'eu' => 'WEU'
    ];

    const ENTITY_TYPE = 'order';

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var ResourceSequenceMeta
     */
    private $sequenceMeta;

    /**
     * @var Profile
     */
    private $profile;

    /**
     * @param StoreManagerInterface $storeManager
     * @param ResourceSequenceMeta $sequenceMeta
     * @param Profile $profile
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        ResourceSequenceMeta $sequenceMeta,
        Profile $profile
    ) {
        $this->storeManager = $storeManager;
        $this->sequenceMeta = $sequenceMeta;
        $this->profile = $profile;
    }

    /**
     * @return array
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function getAliases(): array
    {
        return [];
    }

    /**
     * @return $this
     */
    public function apply(): self
    {
        $stores = $this->storeManager->getStores(true, true);
        foreach ($stores as $store) {
            if (!empty(self::STORE_PREFIXES[$store->getCode()])) {
                try {
                    $sequence = $this->sequenceMeta->loadByEntityTypeAndStore(self::ENTITY_TYPE, $store->getId());
                    $this->profile->loadActiveProfile($sequence->getMetaId())
                    ->setPrefix(self::STORE_PREFIXES[$store->getCode()])->save();
                } catch (\Exception $e) {}
            }
        }
        return $this;
    }
}
