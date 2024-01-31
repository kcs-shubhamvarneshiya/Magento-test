<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Created By : Nirav Modi
 */
namespace Kcs\Pacjson\Model;

class Pacjson extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init('Kcs\Pacjson\Model\ResourceModel\Pacjson');
    }
}
