<?php

namespace Capgemini\BloomreachThematicSitemap\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{
    public const XML_PATH_TO_FIELD = 'bloomreach_thematic/sitemap/';
    public const ENABLED = 'enabled';
    public const DOWNLOAD_LOCATION_MAPS = 'download_location_maps';
    public const MAPS_URLS = 'maps_urls';
    public const APPEND_TO_ROBOTS = 'append_to_robots';
    public const XML_CONFIG_PATH_ROBOT_INSTRUCTIONS = 'design/search_engine_robots/custom_instructions';

    /**
     * @param string $field
     * @return mixed
     */
    public function getConfig(string $field): mixed
    {
        return $this->scopeConfig->getValue(self::XML_PATH_TO_FIELD . $field);
    }

    public function getInstructions()
    {
        return $this->scopeConfig->getValue(self::XML_CONFIG_PATH_ROBOT_INSTRUCTIONS);
    }

    /**
     * @param string|null $url
     * @return string|null
     */
    public function extractFileNameFromUrl(?string $url): ?string
    {
        $urlParts = explode('/', (string) $url);

        return array_pop($urlParts);
    }

    /**
     * @param string $message
     * @param array $context
     * @return void
     */
    public function logError(string $message, array $context = []): void
    {
        $this->_logger->error($message, $context);
    }
}
