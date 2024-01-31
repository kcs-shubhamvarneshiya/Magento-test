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

namespace Capgemini\WholesalePrice\Helper;

use Capgemini\CompanyType\Model\Config;
use Magento\Customer\Model\Customer as CustomerSession;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Customer class
 */
class Customer extends AbstractHelper
{
    public const CUSTOMER_TYPE = 'customer_type';

    public const CUSTOMER_ATTRIBUTES = 'customer_attributes';

    /**
     * @var HttpContext
     */
    protected HttpContext $httpContext;

    /**
     * @var Session
     */
    protected Session $customerSession;

    /**
     * @var Config
     */
    protected Config $companyTypeConfig;

    /**
     * Constructor
     *
     * @param Session $customerSession
     * @param Config $companyTypeConfig
     * @param Context $context
     * @param HttpContext $httpContext
     */
    public function __construct(
        Session $customerSession,
        Config  $companyTypeConfig,
        Context $context,
        HttpContext $httpContext
    ) {
        parent::__construct($context);
        $this->httpContext = $httpContext;
        $this->companyTypeConfig = $companyTypeConfig;
        $this->customerSession = $customerSession;
    }

    /**
     * Get current customer type
     *
     * @param null $customerModel
     * @return null|string
     */
    public function getCustomerType($customerModel = null): ?string
    {
        if ($customerModel) {
            return $this->companyTypeConfig->getCustomerCompanyType($customerModel);
        }

        return $this->getCustomerTypeFromContext()
            ?? $this->companyTypeConfig->getCustomerCompanyType($this->getCustomerFromSession());
    }

    /**
     * @return CustomerSession
     */
    public function getCustomerFromSession(): CustomerSession
    {
        return $this->customerSession->getCustomer();
    }

    /**
     * @param $customerModel
     *
     * @return bool
     */
    public function isCustomerWholesale($customerModel = null): bool
    {
        return $this->getCustomerType($customerModel) == Config::WHOLESALE;
    }

    /**
     * @return mixed|null
     */
    private function getCustomerTypeFromContext()
    {
        return $this->httpContext->getValue(self::CUSTOMER_TYPE);
    }

    /**
     * @param $customerModel
     *
     * @return array|null
     */
    public function getCustomerAttributes($customerModel = null): ?array
    {
        if ($customerModel) {
            return $this->filterCustomerAttributes($customerModel->getData());
        }

        return $this->getCustomerAttributesFromContext()
            ?? $this->filterCustomerAttributes($this->getCustomerFromSession()->getData());
    }

    /**
     * @return mixed|null
     */
    private function getCustomerAttributesFromContext()
    {
        return $this->httpContext->getValue(self::CUSTOMER_ATTRIBUTES);
    }

    /**
     * @param $data
     *
     * @return array
     */
    private function filterCustomerAttributes($data): array
    {
        return array_filter(
            $data,
            function ($key) {
                return strpos((string)$key, 'customer_number') !== false;
            },
            ARRAY_FILTER_USE_KEY
        );
    }

    /**
     * @param $customerData
     *
     * @return void
     *
     * @throws NoSuchEntityException
     */
    public function setCustomerDataToContext($customerData)
    {
        $attributes = $this->filterCustomerAttributes($customerData);

        $value = isset($customerData['entity_id'])
            ? $this->companyTypeConfig->getCompanyTypeByCustomerId($customerData['entity_id'])
            : Config::RETAIL;

        $this->httpContext->setValue(
            self::CUSTOMER_TYPE,
            $value,
            false
        );

        $this->httpContext->setValue(
            self::CUSTOMER_ATTRIBUTES,
            $attributes,
            false
        );
    }
}
