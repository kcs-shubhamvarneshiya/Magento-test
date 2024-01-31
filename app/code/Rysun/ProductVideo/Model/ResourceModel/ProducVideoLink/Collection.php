<?php
declare(strict_types=1);

namespace Rysun\ProductVideo\Model\ResourceModel\ProducVideoLink;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

    /**
     * @inheritDoc
     */
    protected $_idFieldName = 'producvideolink_id';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            \Rysun\ProductVideo\Model\ProducVideoLink::class,
            \Rysun\ProductVideo\Model\ResourceModel\ProducVideoLink::class
        );
    }
}

