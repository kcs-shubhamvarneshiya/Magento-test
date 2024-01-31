<?php

namespace Capgemini\BloomreachProductRecommendations\RequestStringBuilding\RequestStringBuilder;

use Capgemini\BloomreachProductRecommendations\Block\Widget\Recommendation;
use Capgemini\BloomreachProductRecommendations\RequestStringBuilding\RequestStringBuilder;

class KeywordBuilder extends RequestStringBuilder
{
    protected const SPECIFIC_NON_URL_ENCODED_PARAM_TO_DATA_KEY_MAPPING = [
        'query' => Recommendation::WIDGET_OPTION_KEYWORD_QUERY
    ];

    /**
     * @inheritDoc
     */
    protected function defineRequiredParams(): void
    {
        parent::defineRequiredParams();

        $this->requiredParams[] = 'query';
    }
}
