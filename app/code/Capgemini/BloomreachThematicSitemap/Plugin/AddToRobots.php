<?php

namespace Capgemini\BloomreachThematicSitemap\Plugin;

use Capgemini\BloomreachThematicSitemap\Helper\Data as ModuleHelper;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Robots\Block\Data;
use Magento\Store\Model\StoreManagerInterface;

class AddToRobots
{
    private const CURRENT_INSTRUCTION_COMMENT = '#Thematic sitemaps';

    private StoreManagerInterface $storeManager;
    private ModuleHelper $moduleHelper;

    /**
     * @param StoreManagerInterface $storeManager
     * @param ModuleHelper $moduleHelper
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        ModuleHelper $moduleHelper
    ) {

        $this->storeManager = $storeManager;
        $this->moduleHelper = $moduleHelper;
    }

    /**
     * @param Data $subject
     * @param string $result
     * @return string
     */
    public function afterToHtml(Data $subject, string $result): string
    {
        if (!$this->moduleHelper->getConfig(ModuleHelper::ENABLED)) {

            return $result;
        }

        if (!$this->moduleHelper->getConfig(ModuleHelper::APPEND_TO_ROBOTS)) {

            return $result;
        }

        try {
            $baseUrl = $this->storeManager->getStore()->getBaseUrl();
        } catch (\Exception $exception) {
            $this->moduleHelper->logError($exception->getMessage(), ['method' => __METHOD__]);

            return $result;
        }

        if (!$mapUrls = $this->moduleHelper->getConfig(ModuleHelper::MAPS_URLS)) {

            return $result;
        }

        $mapUrls = trim($mapUrls);
        $mapUrls = explode(PHP_EOL, $mapUrls);
        $mapsLoc = $this->moduleHelper->getConfig(ModuleHelper::DOWNLOAD_LOCATION_MAPS);

        $instructions = $result . PHP_EOL . self::CURRENT_INSTRUCTION_COMMENT;
        $instructionPath = $this->getInstructionPath($baseUrl, $mapsLoc);

        foreach ($mapUrls as $mapUrl) {
            $mapUrl = trim($mapUrl);
            $instructions = $this->append($mapUrl, $instructionPath, $instructions);
        }

        return $instructions . PHP_EOL;
    }

    /**
     * @param $baseUrl
     * @param $mapsLoc
     * @return string
     */
    private function getInstructionPath($baseUrl, $mapsLoc): string
    {
        $baseUrl = rtrim($baseUrl, '/');
        $mapsLoc = trim($mapsLoc, '/');

        if (str_starts_with($mapsLoc, DirectoryList::PUB)) {
            $mapsLoc = substr($mapsLoc, 3);
            $mapsLoc = ltrim($mapsLoc, '/');
        }

        return rtrim($baseUrl . '/' . $mapsLoc, '/');
    }

    /**
     * @param $mapUrl
     * @param $instructionPath
     * @param $instructions
     * @return string
     */
    private function append($mapUrl, $instructionPath, $instructions): string
    {
        $mapFileName = $this->moduleHelper->extractFileNameFromUrl($mapUrl);
        $mapFile = $instructionPath . '/' . $mapFileName;

        $currentInstruction = sprintf('Sitemap: %s',trim($mapFile));

        return $instructions . PHP_EOL . $currentInstruction;
    }
}
