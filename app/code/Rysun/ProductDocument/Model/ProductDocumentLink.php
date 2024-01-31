<?php
declare(strict_types=1);

namespace Rysun\ProductDocument\Model;

use Magento\Framework\Model\AbstractModel;
use Rysun\ProductDocument\Api\Data\ProductDocumentLinkInterface;

class ProductDocumentLink extends AbstractModel implements ProductDocumentLinkInterface
{

    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(\Rysun\ProductDocument\Model\ResourceModel\ProductDocumentLink::class);
    }

    /**
     * @inheritDoc
     */
    public function getProductdocumentlinkId()
    {
        return $this->getData(self::PRODUCTDOCUMENTLINK_ID);
    }

    /**
     * @inheritDoc
     */
    public function setProductdocumentlinkId($productdocumentlinkId)
    {
        return $this->setData(self::PRODUCTDOCUMENTLINK_ID, $productdocumentlinkId);
    }

    /**
     * @inheritDoc
     */
    public function getSqlServProdId()
    {
        return $this->getData(self::SQL_SERV_PROD_ID);
    }

    /**
     * @inheritDoc
     */
    public function setSqlServProdId($sqlServProdId)
    {
        return $this->setData(self::SQL_SERV_PROD_ID, $sqlServProdId);
    }

    /**
     * @inheritDoc
     */
    public function getDocumentId()
    {
        return $this->getData(self::DOCUMENT_ID);
    }

    /**
     * @inheritDoc
     */
    public function setDocumentId($documentId)
    {
        return $this->setData(self::DOCUMENT_ID, $documentId);
    }

    /**
     * @inheritDoc
     */
    public function getSortOrder()
    {
        return $this->getData(self::SORT_ORDER);
    }

    /**
     * @inheritDoc
     */
    public function setSortOrder($sortOrder)
    {
        return $this->setData(self::SORT_ORDER, $sortOrder);
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
}

