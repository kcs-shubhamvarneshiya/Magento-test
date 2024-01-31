<?php
declare(strict_types=1);

namespace Rysun\AttributeRange\Model\ResourceModel\AttributeRange;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

    /**
     * @inheritDoc
     */
    protected $_idFieldName = 'attributerange_id';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            \Rysun\AttributeRange\Model\AttributeRange::class,
            \Rysun\AttributeRange\Model\ResourceModel\AttributeRange::class
        );
    }
}

