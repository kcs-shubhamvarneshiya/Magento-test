<?php
/**
 * Capgemini_RequestToOrder
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\RequestToOrder\Model\OrderRequest;

use Capgemini\RequestToOrder\Api\Data\OrderRequest\ItemInterface;
use Capgemini\RequestToOrder\Api\Data\OrderRequestInterface;
use Capgemini\RequestToOrder\Api\OrderRequestRepositoryInterface;
use Capgemini\RequestToOrder\Model\ResourceModel\OrderRequest\Item as ItemResource;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Block\Product\ImageFactory;
use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;

class Item extends AbstractModel implements ItemInterface
{
    /**
     * @var OrderRequestInterface
     */
    private $orderRequest;

    /**
     * @var ImageFactory
     */
    protected $imageFactory;

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var OrderRequestRepositoryInterface
     */
    protected $requestToOrderRepository;


    protected $product;

    /**
     * @param OrderRequestRepositoryInterface $requestToOrderRepository
     * @param ImageFactory $imageFactory
     * @param ProductRepository $productRepository
     * @param Context $context
     * @param Registry $registry
     * @param AbstractDb|null $resourceCollection
     * @param AbstractResource|null $resource
     * @param array $data
     */
    public function __construct(
        OrderRequestRepositoryInterface $requestToOrderRepository,
        ImageFactory                    $imageFactory,
        ProductRepository               $productRepository,
        Context                         $context,
        Registry                        $registry,
        AbstractDb                      $resourceCollection = null,
        AbstractResource                $resource = null,
        array                           $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection,
            $data
        );
        $this->imageFactory = $imageFactory;
        $this->productRepository = $productRepository;
        $this->requestToOrderRepository = $requestToOrderRepository;
    }

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(ItemResource::class);
    }

    /**
     * @return string
     * @throws NoSuchEntityException
     */
    public function getImageUrl()
    {
        return $this->imageFactory->create($this->getProduct(), 'product_thumbnail_image')->getImageUrl();
    }

    /**
     * @return ProductInterface|mixed|null
     * @throws NoSuchEntityException
     */
    public function getProduct()
    {
        if ($this->product === null) {
            $this->product = $this->productRepository->getById($this->getProductId());
        }
        return $this->product;
    }

    /**
     * {@inheritdoc}
     */
    public function getRtoItemId(): ?int
    {
        return (int)$this->getData(self::ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setRtoItemId(?string $rtoItemId)
    {
        $this->setData(self::ID, $rtoItemId);
    }

    /**
     * {@inheritdoc}
     */
    public function getRtoId(): ?int
    {
        return (int)$this->getData(self::RTO_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setRtoId(?string $rtoId)
    {
        $this->setData(self::RTO_ID, $rtoId);
    }

    /**
     * @return Item|void
     * @throws NoSuchEntityException
     */
    public function beforeSave()
    {
        parent::beforeSave();
        if ($this->getRequest()) {
            $this->setRtoId($this->getRequest()->getId());
        }
    }

    /**
     * @return OrderRequestInterface|null
     * @throws NoSuchEntityException
     */
    public function getRequest(): ?OrderRequestInterface
    {
        if ($this->orderRequest === null) {
            $this->orderRequest = $this->requestToOrderRepository->getById($this->getRtoId());
        }

        return $this->orderRequest;
    }

    /**
     * @param OrderRequestInterface|null $request
     * @return void
     */
    public function setRequest(?OrderRequestInterface $request): void
    {
        $this->orderRequest = $request;
        if ($request !== null && $request->getId()) {
            $this->setRtoId($request->getId());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getProductId(): int
    {
        return (int)$this->getData(self::PRODUCT_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setProductId(int $productId)
    {
        $this->setData(self::PRODUCT_ID, $productId);
    }

    /**
     * {@inheritdoc}
     */
    public function getSku(): ?string
    {
        return $this->getData(self::SKU);
    }

    /**
     * {@inheritdoc}
     */
    public function setSku(string $sku): void
    {
        $this->setData(self::SKU, $sku);
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
    public function setName(string $name): void
    {
        $this->setData(self::NAME, $name);
    }

    /**
     * {@inheritdoc}
     */
    public function getQty(): int
    {
        return (int)$this->getData(self::QTY);
    }

    /**
     * {@inheritdoc}
     */
    public function setQty(int $qty): void
    {
        $this->setData(self::QTY, $qty);
    }

    /**
     * {@inheritdoc}
     */
    public function getPrice(): float
    {
        return (float)$this->getData(self::PRICE);
    }

    /**
     * {@inheritdoc}
     */
    public function setPrice(float $price): void
    {
        $this->setData(self::PRICE, $price);
    }
}
