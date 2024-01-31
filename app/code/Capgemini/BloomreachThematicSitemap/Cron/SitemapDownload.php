<?php

namespace Capgemini\BloomreachThematicSitemap\Cron;

use Capgemini\BloomreachThematicSitemap\Model\Downloader;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\CronException;
use Magento\Framework\Filesystem;
use Capgemini\BloomreachThematicSitemap\Helper\Data as ModuleHelper;

class SitemapDownload
{
    private Filesystem $filesystem;
    private Downloader $downloader;
    private ModuleHelper $moduleHelper;

    /**
     * @param Filesystem $filesystem
     * @param Downloader $downloader
     * @param ModuleHelper $moduleHelper
     */
    public function __construct(
        Filesystem $filesystem,
        Downloader $downloader,
        ModuleHelper $moduleHelper
    ) {
        $this->filesystem = $filesystem;
        $this->downloader = $downloader;
        $this->moduleHelper = $moduleHelper;
    }

    /**
     * @throws CronException
     */
    public function execute(): void
    {
        try {
            $pubDirWrite = $this->filesystem->getDirectoryWrite(DirectoryList::PUB);
            $this->downloader->downloadMap($pubDirWrite);
        } catch (\Exception $exception) {
            $this->moduleHelper->logError($exception->getMessage(), ['method' => __METHOD__]);

            throw new CronException(__($exception->getMessage()));
        }

    }


}
