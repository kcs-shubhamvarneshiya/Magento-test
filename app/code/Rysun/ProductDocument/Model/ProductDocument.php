<?php
declare(strict_types=1);

namespace Rysun\ProductDocument\Model;

use Magento\Framework\Model\AbstractModel;
use Rysun\ProductDocument\Api\Data\ProductDocumentInterface;

class ProductDocument extends AbstractModel implements ProductDocumentInterface
{

    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(\Rysun\ProductDocument\Model\ResourceModel\ProductDocument::class);
    }

    /**
     * @inheritDoc
     */
    public function getProductdocumentId()
    {
        return $this->getData(self::PRODUCTDOCUMENT_ID);
    }

    /**
     * @inheritDoc
     */
    public function setProductdocumentId($productdocumentId)
    {
        return $this->setData(self::PRODUCTDOCUMENT_ID, $productdocumentId);
    }

    /**
     * @inheritDoc
     */
    public function getDocumentCaption()
    {
        return $this->getData(self::DOCUMENT_CAPTION);
    }

    /**
     * @inheritDoc
     */
    public function setDocumentCaption($documentCaption)
    {
        return $this->setData(self::DOCUMENT_CAPTION, $documentCaption);
    }

    /**
     * @inheritDoc
     */
    public function getDocumentDescription()
    {
        return $this->getData(self::DOCUMENT_DESCRIPTION);
    }

    /**
     * @inheritDoc
     */
    public function setDocumentDescription($documentDescription)
    {
        return $this->setData(self::DOCUMENT_DESCRIPTION, $documentDescription);
    }

    /**
     * @inheritDoc
     */
    public function getDocumentUrl()
    {
        return $this->getData(self::DOCUMENT_URL);
    }

    /**
     * @inheritDoc
     */
    public function setDocumentUrl($documentUrl)
    {
        return $this->setData(self::DOCUMENT_URL, $documentUrl);
    }

    /**
     * @inheritDoc
     */
    public function getDocumentFileName()
    {
        return $this->getData(self::DOCUMENT_FILE_NAME);
    }

    /**
     * @inheritDoc
     */
    public function setDocumentFileName($documentFileName)
    {
        return $this->setData(self::DOCUMENT_FILE_NAME, $documentFileName);
    }

    /**
     * @inheritDoc
     */
    public function getDocumentType()
    {
        return $this->getData(self::DOCUMENT_TYPE);
    }

    /**
     * @inheritDoc
     */
    public function setDocumentType($documentType)
    {
        return $this->setData(self::DOCUMENT_TYPE, $documentType);
    }

    /**
     * @inheritDoc
     */
    public function getIsActive()
    {
        return $this->getData(self::IS_ACTIVE);
    }

    /**
     * @inheritDoc
     */
    public function setIsActive($isActive)
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }

    /**
     * @inheritDoc
     */
    public function getSqlServId()
    {
        return $this->getData(self::SQL_SERV_ID);
    }

    /**
     * @inheritDoc
     */
    public function setSqlServId($sqlServId)
    {
        return $this->setData(self::SQL_SERV_ID, $sqlServId);
    }
}

