<?php
declare(strict_types=1);

namespace Rysun\AttributeRange\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class AttributeRange extends AbstractDb
{

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('rysun_attributerange_attributerange', 'attributerange_id');
    }
}

