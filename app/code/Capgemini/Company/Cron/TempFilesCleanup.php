<?php

namespace Capgemini\Company\Cron;

use Capgemini\Company\Model\ResourceModel\Company\Document\Contents;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Psr\Log\LoggerInterface;

class TempFilesCleanup
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(Filesystem $filesystem, LoggerInterface $logger)
    {
        $this->filesystem = $filesystem;
        $this->logger = $logger;
    }

    public function execute()
    {
        try {
            $writeInstance = $this->filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
            $tempSaveDirPath = $writeInstance->getAbsolutePath(Contents::MAIN_SAVE_DIRECTORY . '/tmp');
            $filenames = $writeInstance->getDriver()->readDirectory($tempSaveDirPath);
            foreach ($filenames as $filename) {
                if (time() - filectime($filename) > 86400) {
                    $writeInstance->delete($filename);
                }
            }
        } catch (\Exception $exception) {
            $this->logger->error('Capgemini_Company temporary files cleanup: ' . $exception->getMessage());
        }
    }

}
