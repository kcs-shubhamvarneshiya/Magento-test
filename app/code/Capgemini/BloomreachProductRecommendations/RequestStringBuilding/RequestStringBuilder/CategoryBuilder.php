<?php

namespace Capgemini\BloomreachProductRecommendations\RequestStringBuilding\RequestStringBuilder;

use Capgemini\BloomreachProductRecommendations\Block\Widget\Recommendation;
use Capgemini\BloomreachProductRecommendations\RequestStringBuilding\RequestStringBuilder;

class CategoryBuilder extends RequestStringBuilder
{
    protected const SPECIFIC_NON_URL_ENCODED_PARAM_TO_DATA_KEY_MAPPING = [
        'categoryId' => Recommendation::WIDGET_OPTION_CATEGORY_ID,
        'cat_id'     => Recommendation::WIDGET_OPTION_CATEGORY_ID
    ];

    /**
     * @inheritDoc
     */
    protected function defineRequiredParams(): void
    {
        parent::defineRequiredParams();

        $this->requiredParams[] = 'categoryId';
        $this->requiredParams[] = 'cat_id';
    }
}
