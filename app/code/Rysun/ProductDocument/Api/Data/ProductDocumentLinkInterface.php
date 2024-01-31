<?php
declare(strict_types=1);

namespace Rysun\ProductDocument\Api\Data;

interface ProductDocumentLinkInterface
{

    const IS_ACTIVE = 'is_active';
    const DOCUMENT_ID = 'document_id';
    const PRODUCTDOCUMENTLINK_ID = 'productdocumentlink_id';
    const SORT_ORDER = 'sort_order';
    const SQL_SERV_PROD_ID = 'sql_serv_prod_id';

    /**
     * Get productdocumentlink_id
     * @return string|null
     */
    public function getProductdocumentlinkId();

    /**
     * Set productdocumentlink_id
     * @param string $productdocumentlinkId
     * @return \Rysun\ProductDocument\ProductDocumentLink\Api\Data\ProductDocumentLinkInterface
     */
    public function setProductdocumentlinkId($productdocumentlinkId);

    /**
     * Get sql_serv_prod_id
     * @return string|null
     */
    public function getSqlServProdId();

    /**
     * Set sql_serv_prod_id
     * @param string $sqlServProdId
     * @return \Rysun\ProductDocument\ProductDocumentLink\Api\Data\ProductDocumentLinkInterface
     */
    public function setSqlServProdId($sqlServProdId);

    /**
     * Get document_id
     * @return string|null
     */
    public function getDocumentId();

    /**
     * Set document_id
     * @param string $documentId
     * @return \Rysun\ProductDocument\ProductDocumentLink\Api\Data\ProductDocumentLinkInterface
     */
    public function setDocumentId($documentId);

    /**
     * Get sort_order
     * @return string|null
     */
    public function getSortOrder();

    /**
     * Set sort_order
     * @param string $sortOrder
     * @return \Rysun\ProductDocument\ProductDocumentLink\Api\Data\ProductDocumentLinkInterface
     */
    public function setSortOrder($sortOrder);

    /**
     * Get is_active
     * @return string|null
     */
    public function getIsActive();

    /**
     * Set is_active
     * @param string $isActive
     * @return \Rysun\ProductDocument\ProductDocumentLink\Api\Data\ProductDocumentLinkInterface
     */
    public function setIsActive($isActive);
}

