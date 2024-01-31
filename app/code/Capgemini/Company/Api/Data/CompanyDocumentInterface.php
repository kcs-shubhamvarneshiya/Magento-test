<?php

namespace Capgemini\Company\Api\Data;

interface CompanyDocumentInterface
{
    const DOCUMENT_ID = 'document_id';
    const DOCUMENT_TYPE = 'document_type';
    const COMPANY_ID = 'company_id';
    const ADDED_BY = 'added_by';
    const FILENAME = 'filename';
    const CREATED_AT = 'created_at';
    const SYSTEM_FILENAME = 'system_filename';
    const CONTENTS = 'contents';

    const TYPE_PROOF = 'proof';
    const TYPE_TAX_EXEMPT = 'tax-exempt';

    /**
     * @return mixed
     */
    public function getId();

    /**
     * @return mixed
     */
    public function getDocumentId();

    /**
     * @param $documentId
     * @return $this
     */
    public function setDocumentId($documentId);

    /**
     * @return mixed
     */
    public function getDocumentType();

    /**
     * @param $documentType
     * @return $this
     */
    public function setDocumentType($documentType);

    /**
     * @return mixed
     */
    public function getCompanyId();

    /**
     * @param $companyId
     * @return $this
     */
    public function setCompanyId($companyId);

    /**
     * @return string
     */
    public function getAddedBy();

    /**
     * @param string $addedBy
     * @return $this
     */
    public function setAddedBy($addedBy);

    /**
     * @return string
     */
    public function getFilename();

    /**
     * @param string $filename
     * @return $this
     */
    public function setFilename($filename);

    /**
     * @return string
     */
    public function getCreatedAt();

    /**
     * @return string
     */
    public function getContents();

    /**
     * @param string $contents
     * @return $this
     */
    public function setContents($contents);

    /**
     * @return string
     */
    public function getSystemFilename();

    /**
     * @param string $systemFilename
     * @return $this
     */
    public function setSystemFilename($systemFilename);
}
