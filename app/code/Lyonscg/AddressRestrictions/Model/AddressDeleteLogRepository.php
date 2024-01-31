<?php
declare(strict_types=1);

namespace Lyonscg\AddressRestrictions\Model;

use Lyonscg\AddressRestrictions\Api\AddressDeleteLogRepositoryInterface;
use Lyonscg\AddressRestrictions\Api\Data\AddressDeleteLogInterface;
use Lyonscg\AddressRestrictions\Model\AddressDeleteLogFactory;
use Lyonscg\AddressRestrictions\Model\ResourceModel\AddressDeleteLog\Collection as AddressDeleteLogCollection;
use Lyonscg\AddressRestrictions\Model\ResourceModel\AddressDeleteLog\CollectionFactory;
use Lyonscg\AddressRestrictions\Model\ResourceModel\AddressDeleteLog as AddressDeleteLogResource;
use Magento\Customer\Model\Address;
use Magento\Framework\Serialize\Serializer\Json;

class AddressDeleteLogRepository implements AddressDeleteLogRepositoryInterface
{
    /**
     * @var \Lyonscg\AddressRestrictions\Model\AddressDeleteLogFactory
     */
    protected $addressDeleteLogFactory;

    /**
     * @var AddressDeleteLogResource
     */
    protected $addressDeleteLogResource;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var Json
     */
    protected $jsonSerializer;

    /**
     * AddressDeleteLogRepository constructor.
     * @param \Lyonscg\AddressRestrictions\Model\AddressDeleteLogFactory $addressDeleteLogFactory
     * @param AddressDeleteLogResource $addressDeleteLogResource
     * @param CollectionFactory $collectionFactory
     * @param Json $jsonSerializer
     */
    public function __construct(
        AddressDeleteLogFactory $addressDeleteLogFactory,
        AddressDeleteLogResource $addressDeleteLogResource,
        CollectionFactory $collectionFactory,
        Json $jsonSerializer
    ) {
        $this->addressDeleteLogFactory = $addressDeleteLogFactory;
        $this->addressDeleteLogResource = $addressDeleteLogResource;
        $this->collectionFactory = $collectionFactory;
        $this->jsonSerializer = $jsonSerializer;
    }

    /**
     * @param $logId
     * @return AddressDeleteLogInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($logId) : AddressDeleteLogInterface
    {
        /** @var $addressDeleteLog AddressDeleteLog */
        $addressDeleteLog = $this->addressDeleteLogFactory->create();
        if ($logId !== null) {
            $addressDeleteLog->setId($logId);
        }
        $this->addressDeleteLogResource->load($addressDeleteLog, $logId, AddressDeleteLogInterface::LOG_ID);
        if (!$addressDeleteLog->getId()) {
            throw \Magento\Framework\Exception\NoSuchEntityException::singleField(
                AddressDeleteLogInterface::LOG_ID,
                $logId
            );
        } else {
            return $addressDeleteLog;
        }
    }

    /**
     * @param $customerId
     * @return AddressDeleteLogCollection
     */
    public function getByCustomerId($customerId) : AddressDeleteLogCollection
    {
        /** @var $collection AddressDeleteLogCollection */
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter(AddressDeleteLogInterface::CUSTOMER_ID, ['eq' => $customerId]);
        return $collection;
    }

    /**
     * @param AddressDeleteLogInterface $addressDeleteLog
     * @return AddressDeleteLogInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(AddressDeleteLogInterface $addressDeleteLog) : AddressDeleteLogInterface
    {
        try {
            $this->addressDeleteLogResource->save($addressDeleteLog);
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(
                __(
                    'Could not save Address Delete Log: %1',
                    $e->getMessage()
                ),
                $e
            );
        }
        return $addressDeleteLog;
    }

    /**
     * @param Address $address
     * @return AddressDeleteLogInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function createFromAddress(Address $address) : AddressDeleteLogInterface
    {
        $data = [
            'entity_id'  => $address->getId(),
            'company'    => $address->getCompany(),
            'telephone'  => $address->getTelephone(),
            'fax'        => $address->getFax(),
            'prefix'     => $address->getPrefix(),
            'firstname'  => $address->getFirstname(),
            'middlename' => $address->getMiddlename(),
            'lastname'   => $address->getLastname(),
            'suffix'     => $address->getSuffix(),
            'street'     => $address->getStreet(),
            'city'       => $address->getCity(),
            'region_id'  => $address->getRegionId(),
            'postcode'   => $address->getPostcode(),
            'country_id' => $address->getCountryId(),
            'vat_id'     => $address->getVatId(),
            'extension_attributes' => (array)$address->getExtensionAttributes()
        ];
        /** @var $addressDeleteLog AddressDeleteLog */
        $addressDeleteLog = $this->addressDeleteLogFactory->create();
        $addressDeleteLog->setAddressId($address->getId())
            ->setCustomerId($address->getCustomerId())
            ->setAddressJson($this->jsonSerializer->serialize($data));
        return $this->save($addressDeleteLog);
    }
}
