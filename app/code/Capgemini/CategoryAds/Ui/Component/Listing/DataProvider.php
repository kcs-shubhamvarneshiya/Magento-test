<?php
/**
 * Capgemini_CategoryAds
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\CategoryAds\Ui\Component\Listing;

use Capgemini\CategoryAds\Api\Data\CategoryAdsInterface;
use Magento\Framework\Api\Search\SearchResultInterface;

class DataProvider extends \Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider
{
    /**
     * {@inheritdoc}
     */
    protected function searchResultToOutput(SearchResultInterface $searchResult): array
    {
        $result = [
            'items'        => [],
            'totalRecords' => $searchResult->getTotalCount(),
        ];

        /** @var CategoryAdsInterface $item */
        foreach ($searchResult->getItems() as $item) {
            $data = $item->getData();

            $result['items'][] = $data;
        }

        return $result;
    }
}
