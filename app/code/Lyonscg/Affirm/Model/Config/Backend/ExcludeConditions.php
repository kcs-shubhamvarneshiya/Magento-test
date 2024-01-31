<?php

namespace Lyonscg\Affirm\Model\Config\Backend;

use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Value;
use \Lyonscg\Affirm\Helper\Rule as ModuleHelper;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;

class ExcludeConditions extends Value
{
    /**
     * @var ModuleHelper
     */
    private $moduleHelper;

    /**
     * @var WriterInterface
     */
    private $configWriter;

    public function __construct(
        ModuleHelper $moduleHelper,
        WriterInterface $configWriter,
        Context $context,
        Registry $registry,
        ScopeConfigInterface $config,
        TypeListInterface $cacheTypeList,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = [])
    {
        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);

        $this->moduleHelper = $moduleHelper;
        $this->configWriter = $configWriter;
    }

    public function beforeSave()
    {
        $serializedExcludeConditions = $this->moduleHelper->prepareSerializedConditions();
        $serialisedMatchingProductIds = $this->moduleHelper->prepareSerializedMatchingProductIds();
        $websiteIds = $this->moduleHelper->retrieveWebsiteIds();

        if (is_array($websiteIds)) {
            $this->configWriter->save(ModuleHelper::XML_CONFIG_PATH_MATCHING_PRODUCT_IDS, $serialisedMatchingProductIds);
        } else {
            $this->configWriter->save(
                ModuleHelper::XML_CONFIG_PATH_MATCHING_PRODUCT_IDS,
                $serialisedMatchingProductIds,
                'websites',
                $websiteIds
            );
        }

        $this->setValue($serializedExcludeConditions);
        return parent::beforeSave();
    }
}
