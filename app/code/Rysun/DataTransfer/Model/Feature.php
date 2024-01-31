<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Created By : Nirav Modi
 */
namespace Rysun\DataTransfer\Model;

class Feature extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init('Rysun\DataTransfer\Model\ResourceModel\Feature');
    }
}
