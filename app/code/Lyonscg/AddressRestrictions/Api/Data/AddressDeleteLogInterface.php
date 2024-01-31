<?php
declare(strict_types=1);

namespace Lyonscg\AddressRestrictions\Api\Data;

use Magento\Framework\DataObject;

interface AddressDeleteLogInterface
{
    const LOG_ID = 'log_id';
    const CUSTOMER_ID = 'customer_id';
    const ADDRESS_ID = 'address_id';
    const DELETED_AT = 'deleted_at';
    const ADDRESS_JSON = 'address_json';

    /**
     * @return mixed
     */
    public function getId();

    /**
     * @param string|int $logId
     * @return self
     */
    public function setId($logId);

    /**
     * @return string|null
     */
    public function getCustomerId() : ?string;

    /**
     * @param string|int $customerId
     * @return self
     */
    public function setCustomerId($customerId);

    /**
     * @return string|null
     */
    public function getAddressId() : ?string;

    /**
     * @param string|int $addressId
     * @return self
     */
    public function setAddressId($addressId);

    /**
     * @return mixed
     */
    public function getDeletedAt() : string;

    /**
     * @param string $deletedAt
     * @return self
     */
    public function setDeletedAt($deletedAt);

    /**
     * @return string
     */
    public function getAddressJson() : string;

    /**
     * @param string $addressJson
     * @return self
     */
    public function setAddressJson($addressJson);

    /**
     * @return DataObject|null
     */
    public function getAddressData() : ?DataObject;
}
