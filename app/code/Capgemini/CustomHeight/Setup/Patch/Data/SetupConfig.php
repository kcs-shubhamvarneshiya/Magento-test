<?php

namespace Capgemini\CustomHeight\Setup\Patch\Data;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Capgemini\CustomHeight\Helper\PriceHeight;

class SetupConfig implements DataPatchInterface
{
    const CONFIG_CACHE_TYPE = 'config';

    /**
     * @var WriterInterface
     */
    private $configWriter;

    /**
     * @var TypeListInterface
     */
    private $cacheTypeList;

    /**
     * @param WriterInterface $configWriter
     * @param TypeListInterface $cacheTypeList
     */
    public function __construct(
        WriterInterface $configWriter,
        TypeListInterface $cacheTypeList
    ) {
        $this->configWriter = $configWriter;
        $this->cacheTypeList = $cacheTypeList;
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * Apply patch
     *
     * @return DataPatchInterface|void
     */
    public function apply()
    {
        $this->configWriter->delete(
            PriceHeight::ROOM_CONFIG,
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT
        );
        $this->cacheTypeList->cleanType(self::CONFIG_CACHE_TYPE);
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }
}
