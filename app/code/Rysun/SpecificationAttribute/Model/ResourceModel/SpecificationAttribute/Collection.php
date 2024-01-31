<?php
declare(strict_types=1);

namespace Rysun\SpecificationAttribute\Model\ResourceModel\SpecificationAttribute;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

    /**
     * @inheritDoc
     */
    protected $_idFieldName = 'specificationattribute_id';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            \Rysun\SpecificationAttribute\Model\SpecificationAttribute::class,
            \Rysun\SpecificationAttribute\Model\ResourceModel\SpecificationAttribute::class
        );
    }
}

