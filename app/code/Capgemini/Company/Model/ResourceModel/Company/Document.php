<?php


namespace Capgemini\Company\Model\ResourceModel\Company;


class Document extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('company_documents', 'document_id');
    }
}
