<?php
declare(strict_types=1);

namespace Rysun\DimensionImages\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class DimensionImages extends AbstractDb
{

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('rysun_dimensionimages_dimensionimages', 'dimensionimages_id');
    }
}

