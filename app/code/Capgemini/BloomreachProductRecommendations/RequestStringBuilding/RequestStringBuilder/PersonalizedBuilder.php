<?php

namespace Capgemini\BloomreachProductRecommendations\RequestStringBuilding\RequestStringBuilder;

use Capgemini\BloomreachProductRecommendations\RequestStringBuilding\RequestStringBuilder;

class PersonalizedBuilder extends RequestStringBuilder
{
    protected const SPECIFIC_NON_URL_ENCODED_PARAM_TO_DATA_KEY_MAPPING = [
        'user_id' => 'user_id'
    ];
}
