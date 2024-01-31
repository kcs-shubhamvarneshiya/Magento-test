<?php
/**
 * Capgemini_TechConfiguratorImport
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\TechConfiguratorImport\Plugin\Model;

use Capgemini\TechConfiguratorImport\Model\Import\Source\JsonFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Magento\Framework\HTTP\Adapter\FileTransferFactory;
use Magento\Framework\Math\Random;
use Magento\ImportExport\Helper\Data;
use Magento\ImportExport\Model\Import;
use Magento\ImportExport\Model\Import\AbstractSource;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\MediaStorage\Model\File\Uploader;

/**
 * Import plugin to allow upload JSON file for product configurator import
 */
class ImportPlugin
{
    /**
     * @var JsonFactory
     */
    protected $jsonAdapterFactory;
    /**
     * @var FileTransferFactory
     */
    protected $httpFactory;
    /**
     * @var Data
     */
    protected $importExportData;
    /**
     * @var UploaderFactory
     */
    protected $uploaderFactory;
    /**
     * @var Random
     */
    protected $random;
    /**
     * @var WriteInterface
     */
    protected $varDirectory;

    public function __construct(
        JsonFactory $jsonAdapterFactory,
        FileTransferFactory $httpFactory,
        Data $importExportData,
        UploaderFactory $uploaderFactory,
        Random $random,
        Filesystem $filesystem
    ) {
        $this->jsonAdapterFactory = $jsonAdapterFactory;
        $this->httpFactory = $httpFactory;
        $this->importExportData = $importExportData;
        $this->uploaderFactory = $uploaderFactory;
        $this->random = $random;
        $this->varDirectory = $filesystem->getDirectoryWrite(DirectoryList::VAR_IMPORT_EXPORT);
    }

    /**
     * Change file upload and validation for the product_configurator import.
     *
     * @param Import $subject
     * @param callable $proceed
     * @return AbstractSource
     */
    public function aroundUploadFileAndGetSource(Import $subject, callable $proceed): AbstractSource
    {
        if ($subject->getEntity() == 'product_configurator') {
            $sourceFile = $this->uploadSource($subject);
            return $this->jsonAdapterFactory->create(['file' => $sourceFile]);
        } else {
            return $proceed();
        }
    }

    /**
     * Upload JSON source file
     *
     * @param Import $subject
     * @return string
     * @throws FileSystemException
     * @throws LocalizedException
     */
    protected function uploadSource(Import $subject)
    {
        /** @var $adapter \Zend_File_Transfer_Adapter_Http */
        $adapter = $this->httpFactory->create();
        if (!$adapter->isValid(Import::FIELD_NAME_SOURCE_FILE)) {
            $errors = $adapter->getErrors();
            if ($errors[0] == \Zend_Validate_File_Upload::INI_SIZE) {
                $errorMessage = $this->importExportData->getMaxUploadSizeMessage();
            } else {
                $errorMessage = __('The file was not uploaded.');
            }
            throw new LocalizedException($errorMessage);
        }

        $entity = $subject->getEntity();
        /** @var $uploader Uploader */
        $uploader = $this->uploaderFactory->create(['fileId' => Import::FIELD_NAME_SOURCE_FILE]);
        $uploader->setAllowedExtensions(['json']);
        $uploader->skipDbProcessing(true);
        $fileName = $this->random->getRandomString(32) . '.' . $uploader->getFileExtension();
        try {
            $result = $uploader->save($subject->getWorkingDir(), $fileName);
        } catch (\Exception $e) {
            throw new LocalizedException(__('The file cannot be uploaded.'));
        }

        $extension = '';
        $uploadedFile = '';
        if ($result !== false) {
            // phpcs:ignore Magento2.Functions.DiscouragedFunction
            $extension = pathinfo($result['file'], PATHINFO_EXTENSION);
            $uploadedFile = $result['path'] . $result['file'];
        }

        if (!$extension) {
            $this->varDirectory->delete($uploadedFile);
            throw new LocalizedException(__('The file you uploaded has no extension.'));
        }
        $sourceFile = $subject->getWorkingDir() . $entity;

        $sourceFile .= '.' . $extension;
        $sourceFileRelative = $this->varDirectory->getRelativePath($sourceFile);

        if (strtolower($uploadedFile) != strtolower($sourceFile)) {
            if ($this->varDirectory->isExist($sourceFileRelative)) {
                $this->varDirectory->delete($sourceFileRelative);
            }

            try {
                $this->varDirectory->renameFile(
                    $this->varDirectory->getRelativePath($uploadedFile),
                    $sourceFileRelative
                );
            } catch (FileSystemException $e) {
                throw new LocalizedException(__('The source file moving process failed.'));
            }
        }
        return $sourceFile;
    }
}
