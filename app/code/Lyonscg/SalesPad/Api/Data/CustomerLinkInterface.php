<?php

namespace Lyonscg\SalesPad\Api\Data;

interface CustomerLinkInterface
{
    const CUSTOMER_ID = 'customer_id';
    const CUSTOMER_EMAIL = 'customer_email';
    const WEBSITE_ID = 'website_id';
    const SALESPAD_CUSTOMER_NUM = 'sales_pad_customer_num';

    /**
     * @param $customerNum string
     * @return $this
     */
    public function setSalesPadCustomerNum(string $customerNum): CustomerLinkInterface;

    /**
     * @return string
     */
    public function getSalesPadCustomerNum(): string;

    /**
     * @param $id int|null
     * @return $this
     */
    public function setCustomerId(?int $id): CustomerLinkInterface;

    /**
     * @return int|null
     */
    public function getCustomerId(): ?int;

    /**
     * @param $customerEmail string
     * @return $this
     */
    public function setCustomerEmail(string $customerEmail): CustomerLinkInterface;

    /**
     * @return string
     */
    public function getCustomerEmail(): string;

    /**
     * @param $website int|nuLL
     * @return $this
     */
    public function setWebsiteId(?int $website): CustomerLinkInterface;

    /**
     * @return int|null
     */
    public function getWebsiteId(): ?int;
}
