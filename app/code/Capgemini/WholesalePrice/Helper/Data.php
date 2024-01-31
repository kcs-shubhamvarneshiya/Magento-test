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

namespace Capgemini\WholesalePrice\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    public const XML_CONFIG_ENABLED = 'price_validation/wholesale_pricing/enabled';
    public const XML_CONFIG_BRANDS = 'price_validation/wholesale_pricing/advanced_pricing_brands';

    /**
     * @return string|null
     */
    public function isEnabled(): ?string
    {
        return $this->getConfigValue(self::XML_CONFIG_ENABLED);
    }

    /**
     * @return array
     */
    public function getAdvancedPricingBrands(): array
    {
        $value = $this->getConfigValue(self::XML_CONFIG_BRANDS);
        return $value ? explode(',', $value) : [];
    }

    /**
     * @param string $configPath
     * @return string|null
     */
    private function getConfigValue(string $configPath): ?string
    {
        return $this->scopeConfig->getValue(
            $configPath,
            ScopeInterface::SCOPE_STORE
        );
    }
}
