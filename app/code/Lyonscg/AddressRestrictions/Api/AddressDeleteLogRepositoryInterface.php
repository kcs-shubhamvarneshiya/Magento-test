<?php
declare(strict_types=1);

namespace Lyonscg\AddressRestrictions\Api;

use Lyonscg\AddressRestrictions\Api\Data\AddressDeleteLogInterface;
use Lyonscg\AddressRestrictions\Model\ResourceModel\AddressDeleteLog\Collection as AddressDeleteLogCollection;
use Magento\Customer\Model\Address;

interface AddressDeleteLogRepositoryInterface
{
    /**
     * @param $logId
     * @return AddressDeleteLogInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($logId) : AddressDeleteLogInterface;

    /**
     * @param $customerId
     * @return AddressDeleteLogCollection
     */
    public function getByCustomerId($customerId) : AddressDeleteLogCollection;

    /**
     * @param AddressDeleteLogInterface $addressDeleteLog
     * @return AddressDeleteLogInterface
     */
    public function save(AddressDeleteLogInterface $addressDeleteLog) : AddressDeleteLogInterface;

    /**
     * @param Address $address
     * @return AddressDeleteLogInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function createFromAddress(Address $address) : AddressDeleteLogInterface;
}
