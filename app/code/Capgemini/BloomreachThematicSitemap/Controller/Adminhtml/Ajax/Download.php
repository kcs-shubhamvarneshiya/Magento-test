<?php

namespace Capgemini\BloomreachThematicSitemap\Controller\Adminhtml\Ajax;

use Capgemini\BloomreachThematicSitemap\Helper\Data as ModuleHelper;
use Capgemini\BloomreachThematicSitemap\Model\Downloader;
use Exception;
use Magento\Backend\App\Action;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Filesystem;
use Magento\Backend\App\Action\Context;

class Download extends Action
{
    private const SUCCESS_MESSAGE = 'All sitemaps were successfully downloaded.';
    private const ERROR_MESSAGE_TEMPLATE = 'An error occurred while downloading the sitemaps: %s!';

    protected $resultFactory;
    private Filesystem $filesystem;
    private Downloader $downloader;
    private ModuleHelper $moduleHelper;

    public function __construct(
        Context $context,
        ResultFactory $resultFactory,
        Filesystem $filesystem,
        Downloader $downloader,
        ModuleHelper $moduleHelper
    ) {
        parent::__construct($context);
        $this->resultFactory = $resultFactory;
        $this->filesystem = $filesystem;
        $this->downloader = $downloader;
        $this->moduleHelper = $moduleHelper;
    }

    public function execute()
    {
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        try {
            $pubDirWrite = $this->filesystem->getDirectoryWrite(DirectoryList::PUB);
            $this->downloader->downloadMap($pubDirWrite);
        } catch (Exception $exception) {
            $this->moduleHelper->logError($exception->getMessage(), ['method' => __METHOD__]);

            return $resultJson->setData([
                'result'  => 'error',
                'message' => sprintf(self::ERROR_MESSAGE_TEMPLATE, $exception->getMessage())
            ]);
        }

        return $resultJson->setData([
            'result'  => 'success',
            'message' => self::SUCCESS_MESSAGE
        ]);
    }

    protected function _isAllowed()
    {
        return true;
    }
}
