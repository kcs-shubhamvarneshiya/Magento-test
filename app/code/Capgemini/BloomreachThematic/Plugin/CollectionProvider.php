<?php

namespace Capgemini\BloomreachThematic\Plugin;

use Capgemini\BloomreachThematic\Helper\Data as ModuleHelper;
use Capgemini\BloomreachThematic\Model\ResourceModel\Thematic\CollectionFactory;
use Magento\Catalog\Model\Layer\ItemCollectionProviderInterface;
use Magento\Catalog\Model\ResourceModel\Product\Collection;

class CollectionProvider
{
    private ModuleHelper $moduleHelper;
    private CollectionFactory $collectionFactory;

    public function __construct(ModuleHelper $moduleHelper, CollectionFactory $collectionFactory)
    {
        $this->moduleHelper = $moduleHelper;
        $this->collectionFactory = $collectionFactory;
    }

    public function afterGetCollection(ItemCollectionProviderInterface $subject, Collection $result)
    {
        if ($this->moduleHelper->getIsThematicRequest()) {

            $result = $this->collectionFactory->create();
        }

        return $result;
    }
}
