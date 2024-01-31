<?php
/**
 * Capgemini_VcServiceProductPrice
 * php version 7.4.27
 *
 * @category  Capgemini
 * @package   Capgemini_VcServiceProductPrice
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link      https://www.capgemini.com
 */

declare(strict_types=1);

namespace Capgemini\VcServiceProductPrice\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    public const XML_CONFIG_API_KEY = 'price_validation/general/api_key';
    public const XML_CONFIG_ENDPOINT = 'price_validation/general/endpoint';
    public const XML_CONFIG_TIMEOUT = 'price_validation/general/timeout';
    public const XML_CONFIG_DEBUG = 'price_validation/general/debug';
    public const XML_CONFIG_ENDPOINT_LOGIN = 'price_validation/general/login';
    public const XML_CONFIG_ENDPOINT_PASSWORD = 'price_validation/general/password';

    /**
     * @return string|null
     */
    public function getConfigApiKey(): ?string
    {
        return $this->getConfigValue(self::XML_CONFIG_API_KEY);
    }

    /**
     * @return string|null
     */
    public function getConfigEndpoint(): ?string
    {
        return $this->getConfigValue(self::XML_CONFIG_ENDPOINT);
    }

    /**
     * @return string|null
     */
    public function getConfigTimeout(): ?string
    {
        return $this->getConfigValue(self::XML_CONFIG_TIMEOUT);
    }

    /**
     * @return string|null
     */
    public function getConfigEndpointLogin(): ?string
    {
        return $this->getConfigValue(self::XML_CONFIG_ENDPOINT_LOGIN);
    }

    /**
     * @return string|null
     */
    public function getConfigDebug(): ?string
    {
        return $this->getConfigValue(self::XML_CONFIG_DEBUG);
    }

    /**
     * @return string|null
     */
    public function getConfigEndpointPassword(): ?string
    {
        return $this->getConfigValue(self::XML_CONFIG_ENDPOINT_PASSWORD);
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
