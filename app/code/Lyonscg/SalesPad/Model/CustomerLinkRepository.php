<?php

namespace Lyonscg\SalesPad\Model;

use Exception;
use Lyonscg\SalesPad\Api\CustomerLinkRepositoryInterface;
use Lyonscg\SalesPad\Api\Data\CustomerLinkInterface;
use Lyonscg\SalesPad\Model\ResourceModel\CustomerLink as CustomerLinkResource;
use Lyonscg\SalesPad\Model\ResourceModel\CustomerLink\CollectionFactory as CustomerLinkCollectionFactory;
use UnexpectedValueException;

class CustomerLinkRepository implements CustomerLinkRepositoryInterface
{
    /**
     * @var CustomerLinkCollectionFactory
     */
    private $collectionFactory;

    /**
     * @var \Lyonscg\SalesPad\Model\CustomerLinkFactory
     */
    private $customerLinkFactory;

    /**
     * @var CustomerLinkResource
     */
    private $customerLinkResource;

    /**
     * CustomerLinkRepository constructor.
     * @param CustomerLinkCollectionFactory $collectionFactory
     * @param \Lyonscg\SalesPad\Model\CustomerLinkFactory $customerLinkFactory
     * @param CustomerLinkResource $customerLinkResource
     */
    public function __construct(
        CustomerLinkCollectionFactory $collectionFactory,
        CustomerLinkFactory $customerLinkFactory,
        CustomerLinkResource $customerLinkResource
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->customerLinkFactory = $customerLinkFactory;
        $this->customerLinkResource = $customerLinkResource;
    }

    /**
     * @param int|null $id
     * @param string|null $email
     * @param int|null $website
     * @param string $customerNum
     * @return bool
     */
    public function add(?int $id, ?string $email, ?int $website, string $customerNum): bool
    {
        $found = $this->_get($id, $email, $website);
        if ($found->getId()) {
            if ($found->getSalesPadCustomerNum() === $customerNum) {
                return true;
            }
            $found->setSalesPadCustomerNum($customerNum);
            try {
                $this->customerLinkResource->save($found);
                return !!$found->getId();
            } catch (Exception $e) {
                return false;
            }
        } elseif ($id) {
            $found = $this->_get(null, $email, $website);
            if ($found->getId()) {
                $found->setCustomerId($id);
                $found->setSalesPadCustomerNum($customerNum);
                try {
                    $this->customerLinkResource->save($found);
                    return !!$found->getId();
                } catch (Exception $e) {
                    return false;
                }
            }
        }

        /** @var \Lyonscg\SalesPad\Model\CustomerLink $customerLink */
        $customerLink = $this->customerLinkFactory->create();
        $customerLink
            ->setCustomerId($id)
            ->setCustomerEmail($email)
            ->setWebsiteId($website)
            ->setSalesPadCustomerNum($customerNum);
        try {
            $this->customerLinkResource->save($customerLink);
            return !!$customerLink->getId();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @param int|bool|null $id
     * @param string|null $email
     * @param int|null $website
     * @return false|string
     * @throws Exception
     */
    public function get($id, ?string $email, ?int $website)
    {
        $link = $this->_get($id, $email, $website);
        if (!$link->getId()) {
            return false;
        } else {
            return $link->getSalesPadCustomerNum();
        }
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id): CustomerLink
    {
        $customerLinkModel = $this->customerLinkFactory->create();
        $this->customerLinkResource->load($customerLinkModel, $id);

        return $customerLinkModel;
    }

    /**
     * @inheritDoc
     */
    public function save(CustomerLink $link)
    {
        $this->customerLinkResource->save($link);
    }

    /**
     * @inheritDoc
     */
    public function deleteById(int $id)
    {
        $link = $this->getById($id);
        $this->customerLinkResource->delete($link);
    }

    /**
     * @param int $website
     * @param int[] $customerNums
     * @return int[]
     */
    public function getSubsetOfCustomerNumsForWebsite(int $website, array $customerNums): array
    {
        $collection = $this->_getCollection()->filterByCustomerNumsAndWebsite($website, $customerNums);

        return $collection->getColumnValues(CustomerLinkInterface::SALESPAD_CUSTOMER_NUM);
    }

    /**
     * @param int|bool|null $id
     * @param string|null $email
     * @param int|null $website
     * @return \Lyonscg\SalesPad\Model\CustomerLink
     * @throws UnexpectedValueException
     */
    protected function _get($id, ?string $email, ?int $website): CustomerLink
    {
        $collection = $this->_getCollection();
        $collection->filterByCoreIdentifiers($id, $email, $website);

        if ($collection->getSize() > 1) {
            $errorMessage = sprintf(
                'There exist more than one Customer Link at applied filter:
                customer_id: %d, customer_email: %s, website_id: %d',
                $id,
                $email,
                $website
            );
            throw new UnexpectedValueException($errorMessage);
        }

        /** @var \Lyonscg\SalesPad\Model\CustomerLink $link */
        $link = $collection->getFirstItem();

        return $link;
    }

    /**
     * @return \Lyonscg\SalesPad\Model\ResourceModel\CustomerLink\Collection
     */
    protected function _getCollection()
    {
        return $this->collectionFactory->create();
    }
}
