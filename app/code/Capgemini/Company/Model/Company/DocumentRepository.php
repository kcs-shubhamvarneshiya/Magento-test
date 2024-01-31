<?php

namespace Capgemini\Company\Model\Company;

use Capgemini\Company\Api\CompanyDocumentRepositoryInterface;
use Capgemini\Company\Api\Data\CompanyDocumentInterface;
use Capgemini\Company\Api\Data\CompanyDocumentInterfaceFactory;
use Capgemini\Company\Model\ResourceModel\Company\Document as DocumentResourceModel;
use Capgemini\Company\Model\ResourceModel\Company\Document\Collection as DocumentCollection;
use Capgemini\Company\Model\ResourceModel\Company\Document\CollectionFactory as DocumentCollectionFactory;
use Capgemini\Company\Model\ResourceModel\Company\Document\Contents as DocumentContentsResource;
use Magento\Company\Api\Data\CompanyInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class DocumentRepository implements CompanyDocumentRepositoryInterface
{
    /**
     * @var DocumentResourceModel
     */
    protected $documentResource;

    /**
     * @var CompanyDocumentInterfaceFactory
     */
    protected $documentFactory;

    /**
     * @var DocumentContentsResource
     */
    protected $documentContentsResource;

    /**
     * @var DocumentCollectionFactory
     */
    protected $documentCollectionFactory;

    /**
     * DocumentRepository constructor.
     * @param DocumentResourceModel $documentResource
     * @param CompanyDocumentInterfaceFactory $documentFactory
     * @param DocumentContentsResource $documentContentsResource
     * @param DocumentCollectionFactory $documentCollectionFactory
     */
    public function __construct(
        DocumentResourceModel $documentResource,
        CompanyDocumentInterfaceFactory $documentFactory,
        DocumentContentsResource $documentContentsResource,
        DocumentCollectionFactory $documentCollectionFactory
    ) {
        $this->documentResource = $documentResource;
        $this->documentFactory = $documentFactory;
        $this->documentContentsResource = $documentContentsResource;
        $this->documentCollectionFactory = $documentCollectionFactory;
    }

    /**
     * @param CompanyDocumentInterface $document
     * @return CompanyDocumentInterface
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function save(CompanyDocumentInterface $document)
    {
        $this->documentResource->save($document);
        return $document;
    }

    /**
     * @param CompanyDocumentInterface $document
     * @return CompanyDocumentInterface
     * @throws \Exception
     */
    public function delete(CompanyDocumentInterface $document)
    {
        $this->documentContentsResource->deleteContents($document);
        $this->documentResource->delete($document);
        return $document;
    }

    /**
     * @param CompanyDocumentInterface $document
     * @return CompanyDocumentInterface
     */
    public function saveContents(CompanyDocumentInterface $document)
    {
        $this->documentContentsResource->saveContents($document);
        return $document;
    }

    /**
     * @param CompanyDocumentInterface $document
     * @return CompanyDocumentInterface
     * @throws LocalizedException
     */
    public function loadContents(CompanyDocumentInterface $document)
    {
        return $this->documentContentsResource->loadContents($document);
    }

    /**
     * @param $documentId
     * @return CompanyDocumentInterface
     * @throws NoSuchEntityException
     */
    public function getById($documentId)
    {
        $document = $this->documentFactory->create();
        $this->documentResource->load($document, $documentId);
        if (!$document->getId()) {
            throw NoSuchEntityException::singleField('document_id', $documentId);
        }
        return $document;
    }

    /**
     * @param CompanyInterface $company
     * @return DocumentCollection
     */
    public function getForCompany(CompanyInterface $company)
    {
        /** @var DocumentCollection $collection */
        $collection = $this->documentCollectionFactory->create();
        return $collection->addCompanyFilter($company);
    }
}
