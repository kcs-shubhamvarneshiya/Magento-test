<?php
/**
 * Capgemini_TechnicalResources
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\TechnicalResources\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    public const XML_CONFIG_ENABLED = 'technical_resources/general/enabled';
    public const XML_CONFIG_RESOURCE_PATH = 'technical_resources/general/resource_path';
    public const XML_CONFIG_ATTRIBUTE_CODE = 'technical_resources/general/attribute_code';
    public const XML_CONFIG_CAPITALIZE = 'technical_resources/general/capitalize';

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return (bool)$this->getConfigValue(self::XML_CONFIG_ENABLED);
    }

    /**
     * @return string
     */
    public function getResourcePath(): string
    {
        return $this->getConfigValue(self::XML_CONFIG_RESOURCE_PATH);
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

    /**
     * @return string
     */
    public function getAttributeCode(): string
    {
        return $this->getConfigValue(self::XML_CONFIG_ATTRIBUTE_CODE);
    }

    /**
     * @return string
     */
    public function getCapitalize(): string
    {
        return $this->getConfigValue(self::XML_CONFIG_CAPITALIZE);
    }
}
