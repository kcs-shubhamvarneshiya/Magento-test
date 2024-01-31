<?php
/**
 * Capgemini_WholesalePrice
 * php version 7.4.27
 *
 * @category  Capgemini
 * @package   Capgemini_WholesalePrice
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link      https://www.capgemini.com
 */

declare(strict_types=1);

namespace Capgemini\WholesalePrice\Controller\Price;

use Capgemini\WholesalePrice\Model\Price;
use Capgemini\WholesalePrice\Pricing\ApiRender;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Pricing\Price\FinalPrice;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Pricing\Render;
use Magento\Framework\View\Result\Layout;

class PriceBlock implements HttpPostActionInterface
{
    /**
     * @var Http
     */
    protected Http $request;

    /**
     * @var JsonFactory
     */
    protected JsonFactory $jsonResultFactory;

    /**
     * @var ProductRepositoryInterface
     */
    protected ProductRepositoryInterface $productRepository;

    /**
     * @var Price
     */
    protected Price $priceModel;

    /**
     * @var Layout
     */
    protected Layout $resultLayout;

    /**
     * @var RawFactory
     */
    protected RawFactory $rawFactory;

    /**
     * @param Http $request
     * @param JsonFactory $jsonResultFactory
     * @param ProductRepositoryInterface $productRepository
     * @param Price $priceModel
     * @param Layout $resultLayout
     * @param RawFactory $rawFactory
     */
    public function __construct(
        Http $request,
        JsonFactory $jsonResultFactory,
        ProductRepositoryInterface $productRepository,
        Price $priceModel,
        Layout $resultLayout,
        RawFactory $rawFactory
    ) {
        $this->request = $request;
        $this->jsonResultFactory = $jsonResultFactory;
        $this->productRepository = $productRepository;
        $this->priceModel = $priceModel;
        $this->resultLayout = $resultLayout;
        $this->rawFactory = $rawFactory;
    }

    /**
     * @return ResultInterface
     *
     * @throws NoSuchEntityException
     */
    public function execute(): ResultInterface
    {
        if ($productsId = $this->request->getParam('ids')) {
            $jsonResult = $this->jsonResultFactory->create();
            $responseData = [];
            $productsArray = [];

            foreach ($productsId as $productId) {
                $product = $this->productRepository->getById($productId);
                $productsArray[$product->getSku()] = [
                    'id' => $product->getId(),
                    'model' => $product
                ];
            }

            $priceData = $this->priceModel->getCustomerPricesBunch(
                array_column($productsArray, 'model')
            );

            if (!empty($priceData)) {
                foreach ($priceData as $sku => $price) {
                    $responseData[$productsArray[$sku]['id']] = $this->getPriceHtml(
                        $productsArray[$sku]['model'],
                        $price
                    );
                }
            } else {
                foreach ($productsArray as $productData) {
                    $responseData[$productData['id']] = $this->getPriceHtml(
                        $productData['model']
                    );
                }
            }

            $jsonResult->setData($responseData);
            return $jsonResult;
        }

        $rawResult = $this->rawFactory->create();
        $productId = $this->request->getParam('id');

        try {
            $product = $this->productRepository->getById($productId);
            $content = $this->getPriceHtml($product);
        } catch  (\Exception $exception) {
            $content = '';
        }

        $rawResult->setContents($content);
        return $rawResult;
    }

    /**
     * @param $product
     * @param null $preloadedPrice
     *
     * @return string
     */
    private function getPriceHtml($product, $preloadedPrice = null): string
    {
        $price = '';
        try {
            if ($preloadedPrice) {
                $price = $this->renderFinalPriceBlock(
                    'render.product.price.render.ajax.api',
                    $product,
                    $preloadedPrice
                );
            } elseif ($priceAmount = $this->priceModel->getCustomerPrice($product)) {
                $price = $this->renderFinalPriceBlock('render.product.price.render.ajax.api', $product, $priceAmount);
            } else {
                $price = $this->renderFinalPriceBlock('product.price.render.default', $product);
            }
        } catch (\Exception $exception) {
            $price = $this->renderFinalPriceBlock('product.price.render.default', $product);
        }

        return $price;
    }
    /**
     * @param $name
     * @param $product
     * @param $priceAmount
     * @return string
     */
    private function renderFinalPriceBlock($name, $product, $priceAmount = null): string
    {
        $priceRender = $this->resultLayout->getLayout()->getBlock($name);
        $class = Render::class;
        if ($priceAmount) {
            $class = ApiRender::class;
        }
        if (!$priceRender) {
            $priceRender = $this->resultLayout->getLayout()->createBlock(
                $class,
                $name,
                ['data' => ['price_render_handle' => 'catalog_product_prices']]
            );
        }

        $price = '';
        if ($priceRender) {
            if ($priceAmount) {
                $priceRender->setApiPrice($priceAmount);
            }
            $price = $priceRender->render(
                FinalPrice::PRICE_CODE,
                $product,
                [
                    'display_minimal_price'  => true,
                    'use_link_for_as_low_as' => true,
                    'zone' => Render::ZONE_ITEM_LIST
                ]
            );
        }

        return $price;
    }
}
