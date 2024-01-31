<?php

namespace Capgemini\Company\Model\ResourceModel\Company\Document;

use Capgemini\Company\Api\Data\CompanyDocumentInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\WriteInterface;

class Contents
{
    const MAX_SIZE = 16777216;
    const MAIN_SAVE_DIRECTORY = 'company_documents';

    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @param CompanyDocumentInterface $document
     * @return $this
     * @throws LocalizedException
     */
    public function saveContents(CompanyDocumentInterface $document)
    {
        $writeDirInstance = $this->filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
        $fileName = $document->getSystemFilename();
        $contentsFilePath = sprintf(
            '/%s/%s',
            self::MAIN_SAVE_DIRECTORY,
            $fileName
        );
        $writeDirInstance->writeFile($contentsFilePath, $document->getContents());

        return $this;
    }

    /**
     * @param CompanyDocumentInterface $document
     * @return CompanyDocumentInterface
     * @throws LocalizedException
     */
    public function loadContents(CompanyDocumentInterface $document)
    {
        if (!empty($document->getContents())) {
            return $document;
        }

        if (!$document->getId()) {
            return $document;
        }

        $writeDirInstance = $this->filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
        $contentsPath = $this->getSystemFileAbsolutePath($writeDirInstance, $document);
        $contents = $writeDirInstance->getDriver()->fileGetContents($contentsPath);

        return $document->setContents($contents);
    }

    /**
     * @return $this
     * @throws FileSystemException
     */
    public function deleteContents(CompanyDocumentInterface $document)
    {
        $writeDirInstance = $this->filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
        $contentsPath = $this->getSystemFileAbsolutePath($writeDirInstance, $document);
        $writeDirInstance->delete($contentsPath);

        return $this;
    }

    /**
     * @param WriteInterface $writeDirInstance
     * @param CompanyDocumentInterface $document
     * @return string
     */
    private function getSystemFileAbsolutePath(WriteInterface $writeDirInstance, CompanyDocumentInterface $document)
    {
        $mainSaveDirPath = $writeDirInstance->getAbsolutePath(self::MAIN_SAVE_DIRECTORY);
        $systemFileName = $document->getSystemFilename();

        return $mainSaveDirPath . '/' . $systemFileName;
    }
}
