<?php

namespace Capgemini\BloomreachProductRecommendations\RequestStringBuilding;

use Capgemini\BloomreachProductRecommendations\RequestStringBuilding\RequestStringBuilder\CategoryBuilder;
use Capgemini\BloomreachProductRecommendations\RequestStringBuilding\RequestStringBuilder\GlobalBuilder;
use Capgemini\BloomreachProductRecommendations\RequestStringBuilding\RequestStringBuilder\ItemBuilder;
use Capgemini\BloomreachProductRecommendations\RequestStringBuilding\RequestStringBuilder\KeywordBuilder;
use Capgemini\BloomreachProductRecommendations\RequestStringBuilding\RequestStringBuilder\PersonalizedBuilder;
use Magento\Framework\Exception\InvalidArgumentException;
use Magento\Framework\ObjectManagerInterface;

class RequestStringBuilderFactory
{
    private const WIDGET_TYPE_TO_REQUEST_BUILDER = [
        'category'     => CategoryBuilder::class,
        'keyword'      => KeywordBuilder::class,
        'global'       => GlobalBuilder::class,
        'item'         => ItemBuilder::class,
        'personalized' => PersonalizedBuilder::class
    ];

    private ObjectManagerInterface $objectManager;

    /**
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @param string $type
     * @param array $data
     * @return CategoryBuilder|KeywordBuilder|GlobalBuilder|ItemBuilder|PersonalizedBuilder
     * @throws InvalidArgumentException
     */
    public function create(string $type, array $data): KeywordBuilder|GlobalBuilder|ItemBuilder|CategoryBuilder|PersonalizedBuilder
    {
        $className = self::WIDGET_TYPE_TO_REQUEST_BUILDER[$type] ?? null;

        if (!$className) {

            throw new InvalidArgumentException(__('Request builder for' . $type . ' does not exist!'));
        }

        return $this->objectManager->create($className, ['data' => $data]);
    }
}
