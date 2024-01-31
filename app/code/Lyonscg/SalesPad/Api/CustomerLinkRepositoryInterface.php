<?php

namespace Lyonscg\SalesPad\Api;

use Exception;
use Lyonscg\SalesPad\Model\CustomerLink;
use Magento\Framework\Exception\AlreadyExistsException;

interface CustomerLinkRepositoryInterface
{
    /**
     * @param $id int|null
     * @param $email string|null
     * @param $website int|null
     * @param $customerNum string
     * @return bool
     */
    public function add(?int $id, ?string $email, ?int $website, string $customerNum): bool;

    /**
     * @param $id int|bool|null
     * @param $email string|null
     * @param $website int|null
     * @return string|false
     */
    public function get($id, ?string $email, ?int $website);

    /**
     * @param int $id
     * @return CustomerLink
     */
    public function getById(int $id): CustomerLink;

    /**
     * @param CustomerLink $link
     * @return void
     * @throws AlreadyExistsException
     */
    public function save(CustomerLink $link);

    /**
     * @param int $id
     * @throws Exception
     */
    public function deleteById(int $id);

    /**
     * @param int $website
     * @param int[] $customerNums
     * @return int[]
     */
    public function getSubsetOfCustomerNumsForWebsite(int $website, array $customerNums): array;
}
