<?php
declare(strict_types=1);

namespace Rysun\ProductDocument\Api\Data;

interface ProductDocumentInterface
{

    const SQL_SERV_ID = 'sql_serv_id';
    const IS_ACTIVE = 'is_active';
    const DOCUMENT_CAPTION = 'document_caption';
    const DOCUMENT_TYPE = 'document_type';
    const PRODUCTDOCUMENT_ID = 'productdocument_id';
    const DOCUMENT_DESCRIPTION = 'document_description';
    const DOCUMENT_FILE_NAME = 'document_file_name';
    const DOCUMENT_URL = 'document_url';

    /**
     * Get productdocument_id
     * @return string|null
     */
    public function getProductdocumentId();

    /**
     * Set productdocument_id
     * @param string $productdocumentId
     * @return \Rysun\ProductDocument\ProductDocument\Api\Data\ProductDocumentInterface
     */
    public function setProductdocumentId($productdocumentId);

    /**
     * Get document_caption
     * @return string|null
     */
    public function getDocumentCaption();

    /**
     * Set document_caption
     * @param string $documentCaption
     * @return \Rysun\ProductDocument\ProductDocument\Api\Data\ProductDocumentInterface
     */
    public function setDocumentCaption($documentCaption);

    /**
     * Get document_description
     * @return string|null
     */
    public function getDocumentDescription();

    /**
     * Set document_description
     * @param string $documentDescription
     * @return \Rysun\ProductDocument\ProductDocument\Api\Data\ProductDocumentInterface
     */
    public function setDocumentDescription($documentDescription);

    /**
     * Get document_url
     * @return string|null
     */
    public function getDocumentUrl();

    /**
     * Set document_url
     * @param string $documentUrl
     * @return \Rysun\ProductDocument\ProductDocument\Api\Data\ProductDocumentInterface
     */
    public function setDocumentUrl($documentUrl);

    /**
     * Get document_file_name
     * @return string|null
     */
    public function getDocumentFileName();

    /**
     * Set document_file_name
     * @param string $documentFileName
     * @return \Rysun\ProductDocument\ProductDocument\Api\Data\ProductDocumentInterface
     */
    public function setDocumentFileName($documentFileName);

    /**
     * Get document_type
     * @return string|null
     */
    public function getDocumentType();

    /**
     * Set document_type
     * @param string $documentType
     * @return \Rysun\ProductDocument\ProductDocument\Api\Data\ProductDocumentInterface
     */
    public function setDocumentType($documentType);

    /**
     * Get is_active
     * @return string|null
     */
    public function getIsActive();

    /**
     * Set is_active
     * @param string $isActive
     * @return \Rysun\ProductDocument\ProductDocument\Api\Data\ProductDocumentInterface
     */
    public function setIsActive($isActive);

    /**
     * Get sql_serv_id
     * @return string|null
     */
    public function getSqlServId();

    /**
     * Set sql_serv_id
     * @param string $sqlServId
     * @return \Rysun\ProductDocument\ProductDocument\Api\Data\ProductDocumentInterface
     */
    public function setSqlServId($sqlServId);
}

