<?php

namespace Lyonscg\SalesPad\Model\ResourceModel\CustomerLink;

use Lyonscg\SalesPad\Api\Data\CustomerLinkInterface;

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
            \Lyonscg\SalesPad\Model\CustomerLink::class,
            \Lyonscg\SalesPad\Model\ResourceModel\CustomerLink::class
        );
    }

    /**
     * @param $email string
     * @return $this
     */
    /*public function filterByCustomerEmail($email)
    {
        $this->addFieldToFilter('customer_email', $email);
        return $this;
    }*/

    /**
     * @param int|bool|null $id
     * @param string|null $email
     * @param int|null $website
     * @return Collection
     */
    public function filterByCoreIdentifiers($id, ?string $email, ?int $website): Collection
    {
        if (is_numeric($id)) {
            $this->addFieldToFilter(CustomerLinkInterface::CUSTOMER_ID, $id);
        } else {
            $nullState = $id === true ? 'notnull' : 'null';
            $this->addFieldToFilter(CustomerLinkInterface::CUSTOMER_ID, [$nullState => true])
                ->addFieldToFilter(CustomerLinkInterface::CUSTOMER_EMAIL, $email)
                ->addFieldToFilter(CustomerLinkInterface::WEBSITE_ID, $website);
        }

        return $this;
    }

    /**
     * @param int $website
     * @param int[] $customerNums
     * @return Collection
     */
    public function filterByCustomerNumsAndWebsite(int $website, array $customerNums): Collection
    {
        return $this->addFieldToFilter(CustomerLinkInterface::WEBSITE_ID, $website)
            ->addFieldToFilter(CustomerLinkInterface::SALESPAD_CUSTOMER_NUM, ['in' => $customerNums]);
    }
}
