<?php
declare(strict_types=1);

namespace Rysun\SpecificationAttribute\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class SpecificationAttribute extends AbstractDb
{

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('rysun_specificationattribute_specificationattribute', 'specificationattribute_id');
    }
}

