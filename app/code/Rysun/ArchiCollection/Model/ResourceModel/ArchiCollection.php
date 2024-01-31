<?php
declare(strict_types=1);

namespace Rysun\ArchiCollection\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ArchiCollection extends AbstractDb
{

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('rysun_archicollection_archi_collection', 'archi_collection_id');
    }
}

