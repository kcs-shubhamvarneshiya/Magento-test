<?php

namespace Capgemini\Company\Helper;

use Capgemini\Company\Api\Data\CompanyDocumentInterface;
use Capgemini\Company\Api\Data\CompanyDocumentInterfaceFactory;
use Capgemini\Company\Api\CompanyDocumentRepositoryInterface;
use Magento\Company\Api\Data\CompanyInterface;
use Magento\Company\Api\CompanyRepositoryInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Filesystem;

class Document extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $documentFactory;

    protected $documentRepository;

    protected $filesystem;

    protected $companyRepository;

    protected $customerRepository;

    public function __construct(
        Context $context,
        CompanyDocumentInterfaceFactory $documentFactory,
        CompanyDocumentRepositoryInterface $documentRepository,
        Filesystem $filesystem,
        CompanyRepositoryInterface $companyRepository,
        CustomerRepositoryInterface $customerRepository
    ) {
        parent::__construct($context);
        $this->documentFactory = $documentFactory;
        $this->documentRepository = $documentRepository;
        $this->filesystem = $filesystem;
        $this->companyRepository = $companyRepository;
        $this->customerRepository = $customerRepository;
    }

    public function createDocument($realFileName, $uploader, $fileName, $documentType, $companyId)
    {
        /**
         * 1. create a Company\Document model
         * 2. set the document_type, company_name filename, and added_by ($uploader)
         * 3. save with repository
         * 4. load file data and save in database in the company_document_contents table using the id from the model
         * 5. delete the file from server
         * 6. return the model
         */

        // document exists, save the file
        $dir = $this->filesystem->getDirectoryRead(DirectoryList::VAR_DIR);
        $relativePath = $dir->getRelativePath($realFileName);
        if (!$dir->isFile($relativePath) || !$dir->isReadable($relativePath)) {
            // put an error message for the user so they know to re-upload the document
            return false;
        }

        /** @var CompanyDocumentInterface $document */
        $document = $this->documentFactory->create();
        $document->setAddedBy($uploader);
        $document->setCompanyId($companyId);
        $document->setDocumentType($documentType);
        $document->setFilename($fileName);
        $document->setSystemFilename($this->buildSystemFileName($companyId, $fileName));

        $this->documentRepository->save($document);
        if (!$document->getId()) {
            // put an error message to user
            return false;
        }

        $success = true;
        try {
            $content = $dir->readFile($realFileName);
            $document->setContents($content);
            $this->documentRepository->saveContents($document);
        } catch (FileSystemException $e) {
            // error message
            $success = false;
        }
        if (!$success) {
            try {
                $this->documentRepository->delete($document);
            } catch (\Exception $e) {
                // error deleting the document?
            }
        }

        try {
            $this->filesystem->getDirectoryWrite(DirectoryList::VAR_DIR)->delete($realFileName);
        } catch (\Exception $exception) {
            $this->_logger->error($exception->getMessage(), [$relativePath]);
        }

        return $document;
    }

    public function getDocumentViewUrl(CompanyDocumentInterface $document)
    {
        if (!$document->getId()) {
            return '';
        }
        return $this->_getUrl('*/*/getDocument', ['document_id' => $document->getId()]);
    }

    public function getDocumentRemoveUrl(CompanyDocumentInterface $document)
    {
        if (!$document->getId()) {
            return '';
        }
        return $this->_getUrl('*/*/removeDocument', ['document_id' => $document->getId()]);
    }

    public function customerCompanyHasDocuments(CustomerInterface $customer)
    {
        if (!$customer || !$customer->getId()) {
            return false;
        }

        try {
            $customer = $this->customerRepository->getById($customer->getId());
        } catch (NoSuchEntityException $e) {
            return false;
        }

        // same logic as CompanyManagement, duplicating here to prevent circular dependency
        $company = null;
        if ($customer->getExtensionAttributes() !== null
            && $customer->getExtensionAttributes()->getCompanyAttributes() !== null
            && $customer->getExtensionAttributes()->getCompanyAttributes()->getCompanyId()
        ) {
            $companyAttributes = $customer->getExtensionAttributes()->getCompanyAttributes();
            try {
                $company = $this->companyRepository->get($companyAttributes->getCompanyId());
            } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
                //If company is not found - just return null
            }
        }

        if ($company === null) {
            return false;
        }

        $documents = $this->documentRepository->getForCompany($company);
        return $documents->getSize() > 0;
    }

    public function buildSystemFileName($companyId, $fileName, $timeDirName = null, $timeStamp = null)
    {
        $timeDirName = $timeDirName ?? date('Y') . date('m');
        $timeStamp = $timeStamp ?? time();
        $fileName = sprintf('%s-%s-%s', $companyId, md5($fileName), $timeStamp);

        return $timeDirName . '/' . $fileName;
    }
}
