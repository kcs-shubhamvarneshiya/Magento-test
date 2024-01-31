<?php
declare(strict_types=1);

namespace Rysun\ProductVideo\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ProducVideoLink extends AbstractDb
{

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('rysun_productvideo_producvideolink', 'producvideolink_id');
    }
}

