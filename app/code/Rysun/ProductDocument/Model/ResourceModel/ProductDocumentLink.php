<?php
declare(strict_types=1);

namespace Rysun\ProductDocument\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ProductDocumentLink extends AbstractDb
{

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('rysun_productdocument_productdocumentlink', 'productdocumentlink_id');
    }
}

