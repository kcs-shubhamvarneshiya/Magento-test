<?php
/**
 * Capgemini_CompanyType
 * php version 7.4.27
 *
 * @category  Capgemini
 * @package   Capgemini_CompanyType
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link      https://www.capgemini.com
 */

declare(strict_types=1);

namespace Capgemini\CompanyType\Block\Product;

use Capgemini\CompanyType\Model\Config;
use Capgemini\CompanyType\Model\Product\PurchasePermission;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Block\Product\View as ProductView;
use Magento\Catalog\Helper\Product;
use Magento\Catalog\Model\ProductTypes\ConfigInterface;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Customer\Model\Session;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Json\EncoderInterface as JsonEncoderInterface;
use Magento\Framework\Locale\FormatInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Stdlib\StringUtils;
use Magento\Framework\Url\EncoderInterface;

class AddToPermissionConfig extends ProductView
{
    /**
     * @var PurchasePermission
     */
    protected PurchasePermission $purchasePermissionValidator;

    /**
     * @var Config
     */
    protected Config $companyTypeConfig;

    /**
     * @param PurchasePermission $productPurchaseValidator
     * @param Config $companyTypeConfig
     * @param Context $context
     * @param EncoderInterface $urlEncoder
     * @param JsonEncoderInterface $jsonEncoder
     * @param StringUtils $string
     * @param Product $productHelper
     * @param ConfigInterface $productTypeConfig
     * @param FormatInterface $localeFormat
     * @param Session $customerSession
     * @param ProductRepositoryInterface $productRepository
     * @param PriceCurrencyInterface $priceCurrency
     * @param array $data
     */
    public function __construct(
        PurchasePermission $productPurchaseValidator,
        Config $companyTypeConfig,
        Context                    $context,
        EncoderInterface           $urlEncoder,
        JsonEncoderInterface       $jsonEncoder,
        StringUtils                $string,
        Product                    $productHelper,
        ConfigInterface            $productTypeConfig,
        FormatInterface            $localeFormat,
        Session                    $customerSession,
        ProductRepositoryInterface $productRepository,
        PriceCurrencyInterface     $priceCurrency,
        array                      $data = []
    ) {
        parent::__construct(
            $context,
            $urlEncoder,
            $jsonEncoder,
            $string,
            $productHelper,
            $productTypeConfig,
            $localeFormat,
            $customerSession,
            $productRepository,
            $priceCurrency,
            $data
        );
        $this->purchasePermissionValidator = $productPurchaseValidator;
        $this->companyTypeConfig = $companyTypeConfig;
    }

    /**
     * @return array
     * @throws NoSuchEntityException
     */
    public function getConfig(): array
    {
        $config = [];
        $product = $this->getProduct();
        $customer = $this->customerSession->getCustomer();
        if ($product->getId()) {
            $config[$product->getId()] = $this->isValid($product, $customer);
            if ($product->getTypeId() == Configurable::TYPE_CODE) {
                $childrenProducts = $product->getTypeInstance()->getUsedProducts($product);
                foreach ($childrenProducts as $childProduct) {
                    $config[$childProduct->getId()] = $this->isValid($childProduct, $customer);
                }
            }
        }
        return $config;
    }

    /**
     * @param $product
     * @param $customer
     * @return bool
     * @throws NoSuchEntityException
     */
    private function isValid($product, $customer): bool
    {
        $isValid = true;
        $customerType = $this->companyTypeConfig->getCustomerCompanyType($customer);
        $isWholesale = $customerType == Config::WHOLESALE;
        if ($isWholesale) {
            $isValid = $this->purchasePermissionValidator->validateProductById(
                $product->getid(),
                $customer
            );
        }
        return $isValid;
    }
}
