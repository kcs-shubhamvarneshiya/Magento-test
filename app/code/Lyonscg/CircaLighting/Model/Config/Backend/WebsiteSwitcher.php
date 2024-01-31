<?php

namespace Lyonscg\CircaLighting\Model\Config\Backend;

use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Store\Model\StoreManagerInterface;

class WebsiteSwitcher extends \Magento\Framework\App\Config\Value
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * WebsiteSwitcher constructor.
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        Context $context,
        Registry $registry,
        ScopeConfigInterface $config,
        TypeListInterface $cacheTypeList,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    )
    {
        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
        $this->storeManager = $storeManager;
    }

    /**
     * Validates default website is selected.
     *
     * @return WebsiteSwitcher
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function beforeSave(): WebsiteSwitcher
    {
        $defaultWebsiteId = $this->storeManager->getDefaultStoreView()->getWebsiteId();
        try {
            $defaultWebsite = $this->storeManager->getWebsite($defaultWebsiteId);
        } catch (\Magento\Framework\Exception\LocalizedException $exception) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Can not figure out default website.'));
        }

        $defaultWebsiteCode = $defaultWebsite->getCode();
        /** @var array $websiteCodes */
        $websiteCodes = $this->getValue();

        if (!in_array($defaultWebsiteCode, $websiteCodes)) {
            throw new \Magento\Framework\Exception\LocalizedException(__(sprintf(
                'The default website (%s) may not be unselected.',
                $defaultWebsite->getName()
            )));
        }

        return parent::beforeSave();
    }
}
