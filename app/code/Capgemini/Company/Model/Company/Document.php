<?php

namespace Capgemini\Company\Model\Company;

use Capgemini\Company\Api\Data\CompanyDocumentInterface;

class Document extends \Magento\Framework\Model\AbstractModel implements CompanyDocumentInterface
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Capgemini\Company\Model\ResourceModel\Company\Document::class);
    }

    /**
     * @return mixed|null
     */
    public function getDocumentId()
    {
        return $this->_getData(self::DOCUMENT_ID);
    }

    /**
     * @return mixed|null
     */
    public function getDocumentType()
    {
        return $this->_getData(self::DOCUMENT_TYPE);
    }

    /**
     * @return mixed|null
     */
    public function getCompanyId()
    {
        return $this->_getData(self::COMPANY_ID);
    }

    /**
     * @return mixed|string|null
     */
    public function getAddedBy()
    {
        return $this->_getData(self::ADDED_BY);
    }

    /**
     * @return mixed|string|null
     */
    public function getFilename()
    {
        return $this->_getData(self::FILENAME);
    }

    /**
     * @return mixed|string|null
     */
    public function getCreatedAt()
    {
        return $this->_getData(self::CREATED_AT);
    }

    /**
     * @param $documentId
     * @return Document
     */
    public function setDocumentId($documentId)
    {
        return $this->setData(self::DOCUMENT_ID, $documentId);
    }

    /**
     * @param $documentType
     * @return Document
     */
    public function setDocumentType($documentType)
    {
        return $this->setData(self::DOCUMENT_TYPE, $documentType);
    }

    /**
     * @param $companyId
     * @return Document
     */
    public function setCompanyId($companyId)
    {
        return $this->setData(self::COMPANY_ID, $companyId);
    }

    /**
     * @param string $addedBy
     * @return Document
     */
    public function setAddedBy($addedBy)
    {
        return $this->setData(self::ADDED_BY, $addedBy);
    }

    /**
     * @param string $filename
     * @return Document
     */
    public function setFilename($filename)
    {
        return $this->setData(self::FILENAME, $filename);
    }

    /**
     * @return mixed|string|null
     */
    public function getContents()
    {
        return $this->_getData(self::CONTENTS);
    }

    /**
     * @param string $contents
     * @return Document
     */
    public function setContents($contents)
    {
        return $this->setData(self::CONTENTS, $contents);
    }

    public function getSystemFilename()
    {
        return $this->_getData(self::SYSTEM_FILENAME);
    }

    public function setSystemFilename($systemFilename)
    {
        return $this->setData(self::SYSTEM_FILENAME, $systemFilename);
    }
}
