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

namespace Capgemini\CompanyType\Model\Product;

use Capgemini\CompanyType\Helper\ReportingBrand;
use Capgemini\CompanyType\Model\Config;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\Exception\NoSuchEntityException;

class PurchasePermission
{
    /**
     * @var ReportingBrand
     */
    protected ReportingBrand $reportingBrandHelper;

    /**
     * @var ProductRepositoryInterface
     */
    protected ProductRepositoryInterface $productRepository;

    /**
     * @var Config
     */
    protected Config $companyTypeConfig;

    /**
     * @var null|ProductInterface
     */
    protected ?ProductInterface $product = null;
    /**
     * @var HttpContext
     */
    private HttpContext $httpContext;

    /**
     * @param ReportingBrand $reportingBrandHelper
     * @param ProductRepositoryInterface $productRepository
     * @param Config $companyTypeConfig
     */
    public function __construct(
        ReportingBrand             $reportingBrandHelper,
        ProductRepositoryInterface $productRepository,
        Config                     $companyTypeConfig,
        HttpContext                $httpContext
    ) {
        $this->reportingBrandHelper = $reportingBrandHelper;
        $this->productRepository = $productRepository;
        $this->companyTypeConfig = $companyTypeConfig;
        $this->httpContext = $httpContext;
    }

    /**
     * @return null|ProductInterface
     */
    public function getProduct(): ?ProductInterface
    {
        return $this->product;
    }

    /**
     * @param ProductInterface $product
     */
    public function setProduct(ProductInterface $product): void
    {
        $this->product = $product;
    }

    /**
     * @param $productId
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getReportingBrandValue($productId)
    {
        $product = $this->getProduct() ?? $this->productRepository->getById($productId);

        return $product->getData('reporting_brand');
    }

    /**
     * @return array
     */
    public function getAttributeMap(): array
    {
        return $this->reportingBrandHelper->getMappedConfigValue();
    }

    /**
     * @param $customer
     * @param $permissionAttribute
     * @return bool
     */
    private function getCustomerPermission($customer, $permissionAttribute): bool
    {
        return (bool)$customer->getData($permissionAttribute);
    }

    /**
     * @param $customer
     * @return bool
     */
    public function canValidate($customer, $canUseContext = false): bool
    {
        $customerType = $this->companyTypeConfig->getCustomerCompanyType($customer);

        if ($customerType == Config::RETAIL) {
            $customerType = $this->httpContext->getValue(\Capgemini\WholesalePrice\Helper\Customer::CUSTOMER_TYPE);
        }

        return $customerType == Config::WHOLESALE;
    }

    /**
     * Validate by loaded product
     *
     * @param $product
     * @param $customer
     *
     * @return bool
     *
     * @throws NoSuchEntityException
     */
    public function validateByProduct($product, $customer): bool
    {
        $this->setProduct($product);

        return $this->validateProductById($product->getid(), $customer);
    }

    /**
     * @param $productId
     * @param $customer
     * @return bool
     * @throws NoSuchEntityException
     */
    public function validateProductById($productId, $customer): bool
    {
        $isProductValid = false;

        $reportingBrandValue = $this->getReportingBrandValue($productId);

        $customerPermissionAttribute = array_search($reportingBrandValue, $this->getAttributeMap());

        if ($customerPermissionAttribute) {
            if ($this->getCustomerPermission($customer, $customerPermissionAttribute) === true) {
                $isProductValid = true;
            }
        }

        return $isProductValid;
    }
}
