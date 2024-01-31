<?php
declare(strict_types=1);

namespace Rysun\DimensionImages\Model\ResourceModel\DimensionImages;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

    /**
     * @inheritDoc
     */
    protected $_idFieldName = 'dimensionimages_id';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            \Rysun\DimensionImages\Model\DimensionImages::class,
            \Rysun\DimensionImages\Model\ResourceModel\DimensionImages::class
        );
    }
}

