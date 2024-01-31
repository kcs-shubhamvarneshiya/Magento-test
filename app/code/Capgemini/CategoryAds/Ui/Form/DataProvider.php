<?php
/**
 * Capgemini_CategoryAds
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\CategoryAds\Ui\Form;

use Capgemini\CategoryAds\Api\Data\CategoryAdsInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Capgemini\CategoryAds\Model\ResourceModel\CategoryAds\CollectionFactory as CategoryAdsCollectionFactory;

class DataProvider extends AbstractDataProvider
{
    /**
     * DataProvider constructor.
     * @param CategoryAdsCollectionFactory $categoryAdsCollectionFactory
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        CategoryAdsCollectionFactory $categoryAdsCollectionFactory,
        $name,
        $primaryFieldName,
        $requestFieldName,
        array $meta = [],
        array $data = []
    ) {
        $this->collection   = $categoryAdsCollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getData() : array
    {
        $result = [];
        foreach ($this->collection as $ad) {
            $data = [
                CategoryAdsInterface::ID => $ad->getId(),
                CategoryAdsInterface::NAME => $ad->getName(),
                CategoryAdsInterface::IS_ENABLED => $ad->getIsEnabled(),
                CategoryAdsInterface::POSITION => $ad->getPosition(),
                CategoryAdsInterface::CONTENT => $ad->getContent(),
                CategoryAdsInterface::CATEGORIES => $ad->getCategories(),
            ];

            $result[$ad->getId()] = $data;
        }

        return $result;
    }
}
