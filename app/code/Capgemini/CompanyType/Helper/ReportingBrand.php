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

namespace Capgemini\CompanyType\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class ReportingBrand extends AbstractHelper
{
    public const XML_CONFIG_BRAND_MAPPING_PATH = 'company/brand_mapping_purchase_limit';
    public const XML_CONFIG_BRAND_ERROR_TEXT_PATH = 'company/brand_mapping_purchase_limit/restricted_product_cart_message';

    public const ERROR_ORIGIN_ID = 1;
    public const ERROR_CODE = 'reporting_brand';
    public const ITEM_ERROR_TEXT = "Please <a href='#' data-post='%1'>Remove</a> this item or add it to your <a href='#' data-post='%2'>Request To Order</a> list";

    /**
     * @var array|string[]
     */
    public array $purchaseLimitAttributes = [
        'can_purchase_vc_signature',
        'can_purchase_vc_fan',
        'can_purchase_vc_modern',
        'can_purchase_vc_arch',
        'can_purchase_vc_studio',
        'can_purchase_gl',
        'can_purchase_gl_fan'
    ];

    /**
     * @return array
     */
    public function getMappedConfigValue(): array
    {
        $attributeValueMap = [];
        foreach ($this->purchaseLimitAttributes as $purchaseLimitAttribute) {
            $attributeValueMap[$purchaseLimitAttribute] = $this->getConfigValue($purchaseLimitAttribute);
        }

        return $attributeValueMap;
    }

    /**
     * @param $websiteId
     * @return mixed
     */
    public function getReportingBrandErrorMessage($websiteId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_CONFIG_BRAND_ERROR_TEXT_PATH,
            ScopeInterface::SCOPE_WEBSITE,
            $websiteId
        );
    }

    /**
     * @param $path
     * @return mixed
     */
    private function getConfigValue($path)
    {
        $configPath = self::XML_CONFIG_BRAND_MAPPING_PATH . '/' . $path;

        return $this->scopeConfig->getValue(
            $configPath
        );
    }
}
