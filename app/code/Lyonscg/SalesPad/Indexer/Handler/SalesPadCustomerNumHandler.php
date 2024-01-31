<?php

namespace Lyonscg\SalesPad\Indexer\Handler;

use Magento\Framework\App\ResourceConnection\SourceProviderInterface;
use Magento\Framework\Indexer\HandlerInterface;

class SalesPadCustomerNumHandler implements HandlerInterface
{

    public function prepareSql(SourceProviderInterface $source, $alias, $fieldInfo)
    {
        $source->getSelect()->joinLeft(
            ['sp' => 'salespad_customer_link'],
            'sp.customer_id=e.entity_id',
            [$fieldInfo['name']]
        );
    }
}
