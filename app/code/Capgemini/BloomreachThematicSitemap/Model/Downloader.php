<?php

namespace Capgemini\BloomreachThematicSitemap\Model;

use Capgemini\BloomreachThematicSitemap\Exception\CurlErrorResponseException;
use Capgemini\BloomreachThematicSitemap\Helper\Data as ModuleHelper;
use Capgemini\Payfabric\Model\Curl;
use Exception;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Zend_Http_Client;

class Downloader
{
    private Curl $curl;
    private ModuleHelper $moduleHelper;

    /**
     * @param Curl $curl
     * @param ModuleHelper $moduleHelper
     */
    public function __construct(
        Curl $curl,
        ModuleHelper $moduleHelper
    ) {
        $this->curl = $curl;
        $this->moduleHelper = $moduleHelper;
    }

    /**
     * @param WriteInterface $dirWrite
     * @return void
     * @throws CurlErrorResponseException
     * @throws FileSystemException
     */
    public function downloadMap(WriteInterface $dirWrite): void
    {
        $apiUrlMaps = $this->moduleHelper->getConfig(ModuleHelper::MAPS_URLS);
        $apiUrlMaps = trim($apiUrlMaps);
        $apiUrlMaps = explode(PHP_EOL, $apiUrlMaps);
        $mapFilesLocation = $this->moduleHelper->getConfig(ModuleHelper::DOWNLOAD_LOCATION_MAPS);
        $mapFilesLocation = str_replace(DirectoryList::PUB, '', $mapFilesLocation);
        $mapFolder = $dirWrite->getAbsolutePath($mapFilesLocation);
        $dirWrite->delete($mapFolder);
        $mapFolder = $dirWrite->getAbsolutePath($mapFilesLocation);

        foreach ($apiUrlMaps as $apiUrlMap) {
            $apiUrlMap = trim($apiUrlMap);
            $mapFileName = $this->moduleHelper->extractFileNameFromUrl($apiUrlMap);
            $content = $this->request($apiUrlMap);
            $mapFile = $mapFolder . '/' . $mapFileName;
            $dirWrite->writeFile($mapFile, $content);
        }
    }

    /**
     * @param $apiUrl
     * @return string
     * @throws CurlErrorResponseException
     */
    private function request($apiUrl): string
    {
        $this->curl->setConfig(['timeout' => 120])
            ->write(
                Zend_Http_Client::GET,
                $apiUrl,
                '1.1',
                ['Accept-Encoding:gzip']
            );
        $responseString = $this->curl->read();

        if ($this->curl->getErrno() !== 0) {
            $this->moduleHelper->logError(
                "Curl Error[" . $this->curl->getErrno() . "]: " . $this->curl->getError(),
                ['method' => __METHOD__]
            );
            throw new CurlErrorResponseException(_($this->curl->getError()));
        }

        return $responseString;
    }
}
