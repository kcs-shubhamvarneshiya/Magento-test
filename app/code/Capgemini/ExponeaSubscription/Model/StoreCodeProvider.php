<?php

namespace Capgemini\ExponeaSubscription\Model;

use Lyonscg\CircaLighting\ViewModel\WebsiteSwitcher;

class StoreCodeProvider
{
    /**
     * @var WebsiteSwitcher
     */
    private $storeResolver;

    /**
     * StoreCodeProvider constructor.
     * @param WebsiteSwitcher $storeResolver
     */
    public function __construct(WebsiteSwitcher $storeResolver)
    {
        $this->storeResolver = $storeResolver;
    }

    public function getConfig()
    {
        $storeDataUsedInTemplate = $this->storeResolver->getStoreDataUsedInTemplate();
        $currentStoreCode = $this->storeResolver->getCurrentStoreCode();
        $additionalVariables['customStoreCode'] = $storeDataUsedInTemplate[$currentStoreCode]['uppercase'] ?? '';

        return $additionalVariables;
    }
}
