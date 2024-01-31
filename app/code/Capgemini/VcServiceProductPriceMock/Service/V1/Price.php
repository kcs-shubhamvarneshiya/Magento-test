<?php
/**
 * Capgemini_VcServiceProductPriceMock
 * php version 7.4.27
 *
 * @category  Capgemini
 * @package   Capgemini_VcServiceProductPriceMock
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link      https://www.capgemini.com
 */

declare(strict_types=1);

namespace Capgemini\VcServiceProductPriceMock\Service\V1;

use Capgemini\VcServiceProductPriceMock\Api\PriceDataInterface;
use Capgemini\VcServiceProductPriceMock\Api\PriceDataInterfaceFactory;
use Capgemini\VcServiceProductPriceMock\Api\ResponseDataInterface;
use Capgemini\VcServiceProductPriceMock\Api\ResponseDataInterfaceFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Directory\Model\PriceCurrency;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\Webapi\Rest\Request;
use Magento\Framework\Webapi\Rest\Response;

class Price implements PriceInterface
{
    public const DEFAULT_ERROR_MESSAGE = 'Your request was not completed';

    public const DEFAULT_SUCCESS_MESSAGE = 'Successfully retrieved %1 out of %2 requested price(s) for product(s)';

    /**
     * @var Request
     */
    protected Request $request;

    /**
     * @var Response
     */
    protected Response $response;

    /**
     * @var ResponseDataInterfaceFactory
     */
    protected ResponseDataInterfaceFactory $responseDataInterface;

    /**
     * @var PriceDataInterfaceFactory
     */
    protected PriceDataInterfaceFactory $priceDataInterface;

    /**
     * @var SerializerInterface
     */
    protected SerializerInterface $serializer;

    /**
     * @var ProductRepositoryInterface
     */
    protected ProductRepositoryInterface $productRepository;

    /**
     * @var PriceCurrency
     */
    protected PriceCurrency $priceCurrency;

    /**
     * @param Request $request
     * @param Response $response
     * @param SerializerInterface $serializer
     * @param ProductRepositoryInterface $productRepository
     * @param ResponseDataInterfaceFactory $responseDataInterface
     * @param PriceDataInterfaceFactory $priceDataInterface
     * @param PriceCurrency $priceCurrency
     */
    public function __construct(
        Request $request,
        Response $response,
        SerializerInterface $serializer,
        ProductRepositoryInterface $productRepository,
        ResponseDataInterfaceFactory $responseDataInterface,
        PriceDataInterfaceFactory $priceDataInterface,
        PriceCurrency $priceCurrency
    ) {
        $this->request = $request;
        $this->response = $response;
        $this->serializer = $serializer;
        $this->productRepository = $productRepository;
        $this->responseDataInterface = $responseDataInterface;
        $this->priceDataInterface = $priceDataInterface;
        $this->priceCurrency = $priceCurrency;
    }

    /**
     * @return bool
     */
    private function validateApiKey(): bool
    {
        return !empty($this->request->getHeader('X-Api-Key'));
    }

    /**
     * @param mixed $products
     * @param string $currency
     * @param mixed|null $accounts
     * @return ResponseDataInterface
     * @throws LocalizedException
     */
    public function getData($products, $currency, $accounts = null): ResponseDataInterface
    {
        $responseData = $this->responseDataInterface->create();

        $success = true;
        $errors = [];
        $productsSkus = [];

        foreach ($products as $productString) {
            $productsSkus = array_merge(
                $productsSkus,
                explode(',', $productString)
            );
        }

        if ($this->validateApiKey()) {
            $hasAccounts = false;
            if (!empty($accounts)) {
                foreach ($accounts as $account) {
                    if (isset($account['account']) && !empty($account['account'])) {
                        $hasAccounts = true;
                        break;
                    }
                }
            }
            if (!$hasAccounts) {
                $errors[] = "Account number is required.";
                $message = self::DEFAULT_ERROR_MESSAGE;
                $success = false;
            } elseif (!empty($productsSkus)) {
                $successCount = 0;
                foreach ($productsSkus as $sku) {
                    try {
                        $product = $this->productRepository->get(trim($sku));

                        $priceData = $this->priceDataInterface->create();
                        $priceData->setSku($sku);
                        $price = $product->getFinalPrice() > 0 ? $product->getFinalPrice() * 0.5 : 0;
                        if ($currency !== 'USD') {
                            $price = $this->priceCurrency->convert($price, null, $currency);
                        }
                        $priceData->setPrice(
                            (float)number_format(
                                $price,
                                2
                            )
                        );
                        $priceData->setUnitOfMeasure('EA');

                        $responseData->addPriceData($priceData);
                        $successCount++;
                    } catch (NoSuchEntityException $e) {
                        $errors[] = "Sku $sku was not found.";
                    }
                }
                $message = __(self::DEFAULT_SUCCESS_MESSAGE, [$successCount, count($productsSkus)])->render();
            } else {
                $errors[] = 'provide product sku(s)';
                $success = false;
                $message = self::DEFAULT_ERROR_MESSAGE;
            }
        } else {
            $errors[] = 'api key is not correct';
            $success = false;
            $message = self::DEFAULT_ERROR_MESSAGE;
        }

        $responseData->setSuccess($success);
        $responseData->setErrors($errors);
        $responseData->setMessage($message);

        return $responseData;
    }
}
