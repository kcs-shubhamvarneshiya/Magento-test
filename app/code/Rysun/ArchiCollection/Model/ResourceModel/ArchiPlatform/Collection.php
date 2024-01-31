<?php
declare(strict_types=1);

namespace Rysun\ArchiCollection\Model\ResourceModel\ArchiPlatform;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

    /**
     * @inheritDoc
     */
    protected $_idFieldName = 'archi_platform_id';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            \Rysun\ArchiCollection\Model\ArchiPlatform::class,
            \Rysun\ArchiCollection\Model\ResourceModel\ArchiPlatform::class
        );
    }
}

