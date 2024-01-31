<?php

namespace Capgemini\PaperModel\ViewModel;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Exception\StateException;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\Url\EncoderInterface;
use Capgemini\PaperModel\Helper\Cart as CartHelper;

class PaperModelProduct implements ArgumentInterface
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var ProductInterface|null|bool;
     */
    protected $paperModelProduct;

    /**
     * @var EncoderInterface
     */
    private $urlEncoder;

    /**
     * @var CartHelper
     */
    private $cartHelper;

    /**
     * @var bool
     */
    protected $isInitialised = false;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        EncoderInterface $urlEncoder,
        CartHelper $cartHelper
    ){
        $this->productRepository = $productRepository;
        $this->urlEncoder = $urlEncoder;
        $this->cartHelper = $cartHelper;
    }

    /**
     * @param string $sku
     * @return $this
     * @throws StateException
     */
    public function init(string $sku)
    {
        if ($this->isInitialised === true) {
            throw new StateException(__('This Paper Model Product is already initialised'));
        }

        try {
            $this->paperModelProduct = $this->productRepository->get($sku);
        } catch (NoSuchEntityException $e) {
            $this->paperModelProduct = false;
        }
        $this->isInitialised = true;

        return $this;
    }

    /**
     * @return bool|ProductInterface|null
     * @throws StateException
     */
    public function getPaperModelProduct()
    {
        if ($this->isInitialised === false) {
            throw new StateException(__('This Paper Model Product is not yet initialised'));
        }

        return $this->paperModelProduct;
    }

    /**
     * @param ProductInterface $product
     * @return array
     */
    public function getAddToCartPostParams(ProductInterface $product)
    {
        $url = $this->getAddToCartUrl($product);
        return [
            'action' => $url,
            'data' => [
                'product' => $product->getEntityId(),
                ActionInterface::PARAM_NAME_URL_ENCODED => $this->urlEncoder->encode($url),
            ]
        ];
    }

    /**
     * @return bool
     * @throws StateException
     */
    public function isSalable()
    {
        try {
            /** @var ProductInterface $paperModelProduct */
            $paperModelProduct = $this->getPaperModelProduct();
        } catch (StateException $exception) {
            $paperModelProduct = null;
        }

        if (!$paperModelProduct) {

            return false;
        }

        return $paperModelProduct->getExtensionAttributes()->getStockItem()->getIsInStock() &&
            $paperModelProduct->isSalable();
    }

    /**
     * @param ProductInterface $product
     * @return string
     */
    protected function getAddToCartUrl(ProductInterface $product): string
    {
//        $additional['_query']['options'] = 'cart';
        return $this->cartHelper->getAddUrl($product);
    }
}
