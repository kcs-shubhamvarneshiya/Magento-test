<?php


namespace Capgemini\Company\Model\ResourceModel\Company\Document;

use Magento\Company\Api\Data\CompanyInterface;


class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Capgemini\Company\Model\Company\Document::class,
            \Capgemini\Company\Model\ResourceModel\Company\Document::class
        );
    }

    public function addCompanyFilter(CompanyInterface $company)
    {
        $companyId = $company->getId() ?: 0;
        $this->addFieldToFilter('company_id', ['eq' => $company->getId()]);
        return $this;
    }
}
