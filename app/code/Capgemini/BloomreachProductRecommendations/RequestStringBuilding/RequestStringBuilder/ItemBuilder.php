<?php

namespace Capgemini\BloomreachProductRecommendations\RequestStringBuilding\RequestStringBuilder;

use Capgemini\BloomreachProductRecommendations\Block\Widget\Recommendation;
use Capgemini\BloomreachProductRecommendations\RequestStringBuilding\RequestStringBuilder;

class ItemBuilder extends RequestStringBuilder
{
    protected const SPECIFIC_URL_ENCODED_PARAM_TO_DATA_KEY_MAPPING = [
        'itemIds' => Recommendation::WIDGET_OPTION_ITEM_IDS,
        'item_ids' => Recommendation::WIDGET_OPTION_ITEM_IDS
    ];

    /**
     * @inheritDoc
     */
    protected function defineRequiredParams(): void
    {
        parent::defineRequiredParams();

        $this->requiredParams[] = 'itemIds';
        $this->requiredParams[] = 'item_ids';
    }
}
