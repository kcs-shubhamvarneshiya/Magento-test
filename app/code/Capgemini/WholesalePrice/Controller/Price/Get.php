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
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;

class Get implements HttpPostActionInterface
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
     * @param Http $request
     * @param JsonFactory $jsonResultFactory
     * @param ProductRepositoryInterface $productRepository
     * @param Price $priceModel
     */
    public function __construct(
        Http $request,
        JsonFactory $jsonResultFactory,
        ProductRepositoryInterface $productRepository,
        Price $priceModel
    ) {
        $this->request = $request;
        $this->jsonResultFactory = $jsonResultFactory;
        $this->productRepository = $productRepository;
        $this->priceModel = $priceModel;
    }

    /**
     * @return ResponseInterface|Redirect|ResultInterface
     */
    public function execute()
    {
        $result = $this->jsonResultFactory->create();
        try {
            $productId = $this->request->getParam('id');
            if ($productId) {
                $product = $this->productRepository->getById($productId);
                $extensionAttributes = $product->getExtensionAttributes();

                if ($extensionAttributes && $extensionAttributes->getConfigurableProductLinks()) {
                    $childIds = $extensionAttributes->getConfigurableProductLinks();
                    if (is_array($childIds) && count($childIds)) {
                        foreach ($childIds as $productId) {
                            $product = $this->productRepository->getById($productId);
                            $prices[$productId] = $this->priceModel->getCustomerPrice($product);
                        }
                        return $result->setData(
                            ['amount' => $prices]
                        );
                    }
                }

                $price = $this->priceModel->getCustomerPrice($product);
                $prices[$productId] = $price;
                $result->setData(
                    ['amount' => $prices]
                );
                return $result;
            }
        } catch (\Exception $exception) {
        }
        return $result;
    }
}
