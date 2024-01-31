<?php

namespace Lyonscg\SalesPad\Model;

use Lyonscg\SalesPad\Api\Data\CustomerLinkInterface;

class CustomerLink extends \Magento\Framework\Model\AbstractModel implements CustomerLinkInterface
{
    protected function _construct()
    {
        $this->_init(\Lyonscg\SalesPad\Model\ResourceModel\CustomerLink::class);
    }

    public function setSalesPadCustomerNum(string $customerNum):CustomerLinkInterface
    {
        return $this->setData(self::SALESPAD_CUSTOMER_NUM, $customerNum);
    }

    public function getSalesPadCustomerNum():string
    {
        return $this->getData(self::SALESPAD_CUSTOMER_NUM);
    }

    public function setCustomerId(?int $id): CustomerLinkInterface
    {
        return $this->setData(self::CUSTOMER_ID, $id);
    }

    public function getCustomerId(): ?int
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    public function setCustomerEmail(string $customerEmail): CustomerLinkInterface
    {
        return $this->setData(self::CUSTOMER_EMAIL, $customerEmail);
    }

    public function getCustomerEmail():string
    {
        return $this->getData(self::CUSTOMER_EMAIL);
    }

    public function setWebsiteId(?int $website): CustomerLinkInterface
    {
        return $this->setData(self::WEBSITE_ID, $website);
    }

    public function getWebsiteId(): ?int
    {
        return $this->getData(self::WEBSITE_ID);
    }
}
