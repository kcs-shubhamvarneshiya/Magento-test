<?php
declare(strict_types=1);

namespace Rysun\ProductDocument\Model\ResourceModel\ProductDocument;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

    /**
     * @inheritDoc
     */
    protected $_idFieldName = 'productdocument_id';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            \Rysun\ProductDocument\Model\ProductDocument::class,
            \Rysun\ProductDocument\Model\ResourceModel\ProductDocument::class
        );
    }
}

