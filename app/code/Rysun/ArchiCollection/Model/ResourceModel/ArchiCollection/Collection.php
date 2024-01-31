<?php
declare(strict_types=1);

namespace Rysun\ArchiCollection\Model\ResourceModel\ArchiCollection;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

    /**
     * @inheritDoc
     */
    protected $_idFieldName = 'archi_collection_id';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            \Rysun\ArchiCollection\Model\ArchiCollection::class,
            \Rysun\ArchiCollection\Model\ResourceModel\ArchiCollection::class
        );
    }
}

