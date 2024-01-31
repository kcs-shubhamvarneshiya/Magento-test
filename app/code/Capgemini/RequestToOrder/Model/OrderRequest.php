<?php
/**
 * Capgemini_RequestToOrder
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\RequestToOrder\Model;

use Capgemini\RequestToOrder\Api\Data\OrderRequest\ItemInterface;
use Capgemini\RequestToOrder\Api\Data\OrderRequestInterface;
use Capgemini\RequestToOrder\Api\OrderRequest\ItemRepositoryInterface;
use Capgemini\RequestToOrder\Model\ResourceModel\OrderRequest as OrderRequestResource;
use Capgemini\RequestToOrder\Model\ResourceModel\OrderRequest\Item\Collection;
use Capgemini\RequestToOrder\Model\ResourceModel\OrderRequest\Item\CollectionFactory as ItemsCollectionFactory;
use Exception;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;

class OrderRequest extends AbstractModel implements OrderRequestInterface
{
    /**
     * @var ItemsCollectionFactory
     */
    private $resourceItemCollectionFactory;

    /**
     * @var ItemRepositoryInterface
     */
    protected $itemRequestRepository;

    /**
     * @var Collection
     */
    private $items = null;

    /**
     * @param ItemRepositoryInterface $itemRequestRepository
     * @param ItemsCollectionFactory $resourceItemCollectionFactory
     * @param Context $context
     * @param Registry $registry
     * @param AbstractDb|null $resourceCollection
     * @param AbstractResource|null $resource
     * @param array $data
     */
    public function __construct(
        ItemRepositoryInterface $itemRequestRepository,
        ItemsCollectionFactory  $resourceItemCollectionFactory,
        Context                 $context,
        Registry                $registry,
        AbstractDb              $resourceCollection = null,
        AbstractResource        $resource = null,
        array                   $data = []

    )
    {
        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection,
            $data
        );
        $this->resourceItemCollectionFactory = $resourceItemCollectionFactory;
        $this->itemRequestRepository = $itemRequestRepository;
    }

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(OrderRequestResource::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getRtoId(): ?string
    {
        return $this->getData(self::ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setRtoId(?string $rtoId)
    {
        $this->setData(self::ID, $rtoId);
    }

    /**
     * @return array|mixed|null
     */
    public function getItemsCollection()
    {
        if ($this->hasItemsCollection()) {
            return $this->getData(OrderRequestInterface::ITEMS_COLLECTION);
        }

        if ($this->items === null) {
            $this->items = $this->resourceItemCollectionFactory->create();
            $this->items->setRequest($this);
        }
        return $this->items;
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        if ($this->getData(self::ITEMS) === null) {
            $items = [];
            foreach ($this->getItemsCollection() as $item) {
                $items[] = $item;
            }
            $this->setItems($items);
        }
        return $this->getData(self::ITEMS);
    }

    /**
     * @param ItemInterface $item
     * @return void
     * @throws Exception
     */
    public function addItem(ItemInterface $item): void
    {
        $item->setRequest($this);
        if (!$item->getId()) {
            if ($loadedItem = $this->getItemByProductId($item->getProductId())) {
                $loadedItem->setQty($loadedItem->getQty() + $item->getQty());
                // $loadedItem->save();
            } else {
                $this->getItemsCollection()->addItem($item);
            }
        }
    }

    /**
     * @param ItemInterface $item
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function deleteItem(ItemInterface $item): bool
    {
        if ($itemId = $item->getId()) {
            $item = $this->getItemById($itemId);
            if ($item) {
                return $this->itemRequestRepository->delete($item);
            }
        }
        return false;
    }

    /**
     * @return bool
     */
    public function itemsCollectionWasSet()
    {
        return $this->items !== null;
    }

    /**
     * @param $productId
     * @return false|ItemInterface
     */
    public function getItemByProductId($productId)
    {
        foreach ($this->getItemsCollection() as $item) {
            if ($item->getProductId() == $productId) {
                return $item;
            }
        }

        return false;
    }

    /**
     * @param $itemId
     * @return false|ItemInterface
     */
    public function getItemById($itemId)
    {
        foreach ($this->getItemsCollection() as $item) {
            if ($item->getId() == $itemId) {
                return $item;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomerId(): ?int
    {
        return (int)$this->getData(self::CUSTOMER_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomerId(?int $customerId): void
    {
        $this->setData(self::CUSTOMER_ID, $customerId);
    }

    /**
     * {@inheritdoc}
     */
    public function getStatus(): ?int
    {
        return (int)$this->getData(self::STATUS);
    }

    /**
     * {@inheritdoc}
     */
    public function setStatus(?int $status): void
    {
        $this->setData(self::STATUS, $status);
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): ?string
    {
        return $this->getData(self::NAME);
    }

    /**
     * {@inheritdoc}
     */
    public function setName(?string $name): void
    {
        $this->setData(self::NAME, $name);
    }

    /**
     * {@inheritdoc}
     */
    public function getEmail(): ?string
    {
        return $this->getData(self::EMAIL);
    }

    /**
     * {@inheritdoc}
     */
    public function setEmail(?string $email): void
    {
        $this->setData(self::EMAIL, $email);
    }

    /**
     * {@inheritdoc}
     */
    public function getPhone(): ?string
    {
        return $this->getData(self::PHONE);
    }

    /**
     * {@inheritdoc}
     */
    public function setPhone(?string $phone): void
    {
        $this->setData(self::PHONE, $phone);
    }

    /**
     * {@inheritdoc}
     */
    public function getCompanyName(): ?string
    {
        return $this->getData(self::COMPANY);
    }

    /**
     * @inheritDoc
     */
    public function getVcAccount(): ?string
    {
        return $this->getData(self::VC_ACCOUNT);
    }

    /**
     * @inheritDoc
     */
    public function setVcAccount(?string $vc_account): void
    {
        $this->setData(self::VC_ACCOUNT, $vc_account);
    }

    /**
     * @inheritDoc
     */
    public function getTechAccount(): ?string
    {
        return $this->getData(self::TECH_ACCOUNT);
    }

    /**
     * @inheritDoc
     */
    public function setTechAccount(?string $tech_account): void
    {
        $this->setData(self::TECH_ACCOUNT, $tech_account);
    }

    /**
     * @inheritDoc
     */
    public function getGlAccount(): ?string
    {
        return $this->getData(self::GL_ACCOUNT);
    }

    /**
     * @inheritDoc
     */
    public function setGlAccount(?string $gl_account): void
    {
        $this->setData(self::GL_ACCOUNT, $gl_account);
    }

    /**
     * {@inheritdoc}
     */
    public function setItems($items): void
    {
        $this->setData(self::ITEMS, $items);
    }

    /**
     * {@inheritdoc}
     */
    public function getComments(): ?string
    {
        return $this->getData(self::COMMENTS);
    }

    /**
     * {@inheritdoc}
     */
    public function setComments(?string $comments)
    {
        $this->setData(self::COMMENTS, $comments);
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt(): ?string
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedAt(?string $createdAt): void
    {
        $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * {@inheritdoc}
     */
    public function getUpdatedAt(): ?string
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * {@inheritdoc}
     */
    public function setUpdatedAt(?string $updatedAt): void
    {
        $this->setData(self::UPDATED_AT, $updatedAt);
    }
}
