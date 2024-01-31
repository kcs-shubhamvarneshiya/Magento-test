<?php
declare(strict_types=1);

namespace Rysun\ProductVideo\Model\ResourceModel\ProductVideo;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

    /**
     * @inheritDoc
     */
    protected $_idFieldName = 'productvideo_id';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            \Rysun\ProductVideo\Model\ProductVideo::class,
            \Rysun\ProductVideo\Model\ResourceModel\ProductVideo::class
        );
    }
}

