<?php
/**
 * Copyright © 2016 Lyons Consulting Group, LLC. All rights reserved.
 */

namespace Lyonscg\ImportExport\Plugin\Model\Scheduled;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\ScheduledImportExport\Model\Scheduled\Operation as ScheduledOperation;

/**
 * Class Operation
 *
 * Plugins for handling scheduled import/export operations
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Operation
{
    /**
     * Var Directory of Magento
     *
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface
     */
    protected $_varDirectory;

    /**
     * Config Value Object
     *
     * @var \Magento\Framework\App\Config\ValueFactory
     */
    protected $_configValueFactory;

    /**
     * Application Cache Manager
     *
     * @var \Magento\Framework\App\CacheInterface
     */
    protected $_cacheManager;

    /**
     * Logger Object
     *
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $_filesystem;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    private $messageManager;

    /**
     * Operation constructor.
     *
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\Framework\App\Config\ValueFactory $configValueFactory
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\App\Config\ValueFactory $configValueFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager
    ) {
        $this->_cacheManager = $context->getCacheManager();
        $this->_logger = $context->getLogger();
        $this->_configValueFactory = $configValueFactory;
        $this->_baseDirectory = $filesystem->getDirectoryWrite(DirectoryList::ROOT);
        $this->_filesystem = $filesystem;
        $this->messageManager = $messageManager;
    }

    /**
     * Find import file if needed
     *
     * If the file_name is empty, scan the directory for a file to use as the
     * file_name
     *
     * @param ScheduledOperation $subject
     * @param ScheduledOperation\OperationInterface $operation
     * @return array
     * @throws \Exception
     */
    public function beforeGetFileSource(
        \Magento\ScheduledImportExport\Model\Scheduled\Operation $subject,
        \Magento\ScheduledImportExport\Model\Scheduled\Operation\OperationInterface $operation
    )
    {
        $fileInfo = $subject->getFileInfo();
        $filePath = $this->_baseDirectory->getAbsolutePath() . $fileInfo['file_path'];
        $fileName = null;

        if (isset($fileInfo['as_directory']) && $fileInfo['as_directory'] == 1) {
            $files = scandir($filePath, SCANDIR_SORT_ASCENDING);
            $file = null;

            foreach ($files as $file) {
                // Skip if file starts with '.' or if this is a directory
                if (is_dir($filePath . '/' . $file) || $file[0] == '.') {
                    continue;
                }
                $fileName = $file;
                break;
            }

            if (null !== $fileName) {
                $fileInfo['file_name'] = $fileName;
                $subject->setFileInfo($fileInfo);
            } else {
                $searchPath = rtrim($fileInfo['file_path'], '\\/') . '/';
                $search = "Import path $searchPath not exists";

                throw new \Exception('Unable to import, no files found. ' . $search);
            }

        }

        return [$operation];
    }

    /**
     * Delete local import file
     *
     * Check operation configuration and delete local import file if configured
     *
     * @param \Magento\ScheduledImportExport\Model\Scheduled\Operation $operation
     * @param $result
     * @return mixed
     */
    public function afterRun(ScheduledOperation $operation, $result)
    {
        if ($operation->getOperationType() == 'import') {
            $fileInfo = $operation->getData('file_info');
            if (!is_array($fileInfo)) {
                $fileInfo = json_decode($fileInfo, true);
            }

            if ($result && isset($fileInfo['archive_directory']) && !empty($fileInfo['archive_directory'])) {
                $this->archiveFile($fileInfo);
            }

            if (isset($fileInfo['delete_local_file']) && $fileInfo['delete_local_file'] == 1) {
                $this->deleteFile($fileInfo);
            }
        }


        return $result;
    }

    /**
     * @param array $fileInfo
     */
    protected function deleteFile(array $fileInfo)
    {
        $filePath = rtrim($fileInfo['file_path'], '\\/') . '/' . $fileInfo['file_name'];

        if (!$this->_baseDirectory->isDirectory($filePath)) {
            $this->_baseDirectory->delete($filePath);
        }
    }

    /**
     * Move file
     *
     * This will copy the file to the archive location and DELETE the original
     *
     * @param array $fileInfo
     */
    protected function archiveFile(array $fileInfo)
    {
        $filePath = rtrim($fileInfo['file_path'], '\\/') . '/' . $fileInfo['file_name'];

        $archiveDirectory = preg_replace_callback('|{{([a-zA-Z\-]+)}}|', function($matches) {
            return date($matches[1], time());
        }, $fileInfo['archive_directory']);
        $archivePath = rtrim($archiveDirectory, '\\/') . '/' . $fileInfo['file_name'];


        if (!$this->_baseDirectory->isDirectory($filePath)) {
            $this->_baseDirectory->copyFile($filePath, $archivePath);
            $this->_baseDirectory->delete($filePath);
        }
    }

    /**
     * Suppress email notifications for file not found
     *
     * If the operation has been configured to suppress email notifications
     * then no emails will be sent for file not found errors.
     *
     * @param \Magento\ScheduledImportExport\Model\Scheduled\Operation $operation
     * @param callable $proceed
     * @param $vars
     * @return \Magento\ScheduledImportExport\Model\Scheduled\Operation
     */
    public function aroundSendEmailNotification(ScheduledOperation $operation, callable $proceed, $vars)
    {
        if ($operation->getOperationType() == 'import') {
            $trace = isset($vars['trace']) ? $vars['trace'] : '';
            $fileInfo = $operation->getOrigData('file_info');
            $filePath = $fileInfo['file_name'];
            $filePath = rtrim($fileInfo['file_path'], '\\/') . '/' . $filePath;
            $search = "Import path $filePath not exists";

            $suppress = (bool)isset($fileInfo['suppress_not_found']) ? $fileInfo['suppress_not_found'] : 0;

            // Bypass error notification
            if ($suppress && strpos($trace, $search) !== false) {
                return $operation;
            }
            if ($suppress && strpos($trace, 'no files found') !== false) {
                return $operation;
            }
        }

        return $proceed($vars);
    }

    /**
     * Override scheduled CRON value
     *
     * If frequency is set to CRON and there is a cron expression defined, then
     * replace the default cron configuration with the defined cron expression.
     *
     * @param ScheduledOperation $operation
     * @param $result
     * @return ScheduledOperation
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function afterAfterSave(ScheduledOperation $operation, $result)
    {
        $status = $operation->getStatus();
        $freq = $operation->getFreq();
        $cronExprString = trim($operation->getCron());

        if ($status == 1
            && $freq == 'C'
            && strlen($cronExprString) >= 9
        ) {
            $this->_updateCron($operation);
        }

        return $result;
    }

    /**
     * Update cron config with custom cron setting
     *
     * @param ScheduledOperation $operation
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _updateCron(ScheduledOperation $operation)
    {
        $cronExprString = trim($operation->getCron());
        $exprPath = $operation->getExprConfigPath();
        $modelPath = $operation->getModelConfigPath();

        try {
            /** @var \Magento\Framework\App\Config\ValueInterface $exprValue */
            $exprValue = $this->_configValueFactory->create()->load($exprPath, 'path');
            $oldCronExprString = $exprValue->getValue();
            if ($oldCronExprString != $cronExprString) {
                $exprValue->setValue($cronExprString)->setPath($exprPath)->save();
                $this->_cacheManager->clean(['crontab']);
            }

            $this->_configValueFactory->create()->load(
                $modelPath,
                'path'
            )->setValue(
                ScheduledOperation::CRON_MODEL
            )->setPath(
                $modelPath
            )->save();
        } catch (\Exception $e) {
            $this->_logger->critical($e);
            throw new \Magento\Framework\Exception\LocalizedException(
                __('We were unable to save the cron expression.')
            );
        }
    }
}
