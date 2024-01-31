<?php

namespace Lyonscg\AddressRestrictions\Model;

use Lyonscg\AddressRestrictions\Api\Data\AddressDeleteLogInterface;
use Lyonscg\AddressRestrictions\Model\ResourceModel\AddressDeleteLog as AddressDeleteLogResource;
use Magento\Framework\DataObject;
use Magento\Framework\DataObjectFactory;
use Magento\Framework\Serialize\Serializer\Json;

class AddressDeleteLog extends \Magento\Framework\Model\AbstractModel implements AddressDeleteLogInterface
{
    /**
     * @var DataObjectFactory
     */
    protected $dataObjectFactory;

    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    protected $jsonSerializer;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        DataObjectFactory $dataObjectFactory,
        Json $jsonSerializer,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->dataObjectFactory = $dataObjectFactory;
        $this->jsonSerializer = $jsonSerializer;
    }

    /**
     * @var string
     */
    protected $_idFieldName = self::LOG_ID;

    protected function _construct()
    {
        $this->_init(AddressDeleteLogResource::class);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->getData(self::LOG_ID);
    }

    /**
     * @param string|int $logId
     * @return self
     */
    public function setId($logId)
    {
        $this->setData(self::LOG_ID, $logId);
    }

    /**
     * @return string|null
     */
    public function getCustomerId() : ?string
    {
        $data = $this->getData(self::CUSTOMER_ID);
        return $data === null ? $data : strval($data);
    }

    /**
     * @param string|int $customerId
     * @return self
     */
    public function setCustomerId($customerId) : self
    {
        $this->setData(self::CUSTOMER_ID, $customerId);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAddressId() : ?string
    {
        $data = $this->getData(self::ADDRESS_ID);
        return $data === null ? $data : strval($data);
    }

    /**
     * @param string|int $addressId
     * @return self
     */
    public function setAddressId($addressId) : self
    {
        $this->setData(self::ADDRESS_ID, $addressId);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeletedAt() : string
    {
        return strval($this->getData(self::DELETED_AT));
    }

    /**
     * @param string $deletedAt
     * @return self
     */
    public function setDeletedAt($deletedAt) : self
    {
        $this->setData(self::DELETED_AT, $deletedAt);
        return $this;
    }

    /**
     * @return string
     */
    public function getAddressJson() : string
    {
        return strval($this->getData(self::ADDRESS_JSON));
    }

    /**
     * @param string $addressJson
     * @return self
     */
    public function setAddressJson($addressJson) : self
    {
        $this->setData(self::ADDRESS_JSON, $addressJson);
        return $this;
    }

    /**
     * @return DataObject|null
     */
    public function getAddressData() : ?DataObject
    {
        try {
            $jsonData = $this->jsonSerializer->unserialize($this->getAddressJson());
        } catch (\InvalidArgumentException $e) {
            return null;
        }
        return $this->dataObjectFactory->create(['data' => $jsonData]);
    }
}
