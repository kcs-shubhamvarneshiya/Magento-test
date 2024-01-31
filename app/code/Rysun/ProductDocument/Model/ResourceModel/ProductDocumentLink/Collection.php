<?php
declare(strict_types=1);

namespace Rysun\ProductDocument\Model\ResourceModel\ProductDocumentLink;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

    /**
     * @inheritDoc
     */
    protected $_idFieldName = 'productdocumentlink_id';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            \Rysun\ProductDocument\Model\ProductDocumentLink::class,
            \Rysun\ProductDocument\Model\ResourceModel\ProductDocumentLink::class
        );
    }
}

