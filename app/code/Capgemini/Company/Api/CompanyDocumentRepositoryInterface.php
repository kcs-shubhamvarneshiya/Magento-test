<?php

namespace Capgemini\Company\Api;

use Capgemini\Company\Api\Data\CompanyDocumentInterface;
use Capgemini\Company\Model\ResourceModel\Company\Document\Collection as DocumentCollection;
use Magento\Company\Api\Data\CompanyInterface;
use Magento\Framework\Exception\NoSuchEntityException;

interface CompanyDocumentRepositoryInterface
{
    /**
     * @param CompanyDocumentInterface $document
     * @return CompanyDocumentInterface
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function save(CompanyDocumentInterface $document);

    /**
     * @param CompanyDocumentInterface $document
     * @return CompanyDocumentInterface
     * @throws \Exception
     */
    public function delete(CompanyDocumentInterface $document);

    /**
     * @param CompanyDocumentInterface $document
     * @return CompanyDocumentInterface
     */
    public function saveContents(CompanyDocumentInterface $document);

    /**
     * @param CompanyDocumentInterface $document
     * @return CompanyDocumentInterface
     */
    public function loadContents(CompanyDocumentInterface $document);

    /**
     * @param $documentId
     * @return CompanyDocumentInterface
     * @throws NoSuchEntityException
     */
    public function getById($documentId);

    /**
     * @param CompanyInterface $company
     * @return DocumentCollection
     */
    public function getForCompany(CompanyInterface $company);
}
