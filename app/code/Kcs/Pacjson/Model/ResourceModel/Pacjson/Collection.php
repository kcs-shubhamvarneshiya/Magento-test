<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Created By : Nirav Modi
 */
namespace Kcs\Pacjson\Model\ResourceModel\Pacjson;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Kcs\Pacjson\Model\Pacjson', 'Kcs\Pacjson\Model\ResourceModel\Pacjson');
    }
}
