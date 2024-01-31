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

use Magento\Framework\App\Cache as CacheApp;
use Magento\Framework\App\Cache\State;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class Cache extends AbstractHelper
{
    public const XML_CONFIG_CACHE_LIFETIME = 'price_validation/wholesale_pricing/advanced_pricing_cache_lifetime';

    public const CACHE_TAG = 'PRICE_API';

    public const CACHE_ID = 'price_api';

    /**
     * @var CacheApp
     */
    protected CacheApp $cache;

    /**
     * @var State
     */
    protected State $cacheState;

    /**
     * @var StoreManagerInterface
     */
    protected StoreManagerInterface $storeManager;

    /**
     * Cache constructor.
     *
     * @param Context $context
     * @param CacheApp $cache
     * @param State $cacheState
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Context               $context,
        CacheApp              $cache,
        State                 $cacheState,
        StoreManagerInterface $storeManager
    ) {
        $this->cache = $cache;
        $this->cacheState = $cacheState;
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * @param $customerId
     * @param $sku
     * @return string
     */
    public function getId($customerId, $sku): string
    {
        return base64_encode( self::CACHE_ID . $customerId . $sku);
    }

    /**
     * @param $cacheId
     * @return bool|string
     */
    public function load($cacheId)
    {
        if ($this->cacheState->isEnabled(self::CACHE_ID)) {
            return $this->cache->load($cacheId);
        }

        return false;
    }

    /**
     * @param $data
     * @param $cacheId
     * @return bool
     */
    public function save($data, $cacheId): bool
    {
        if ($this->cacheState->isEnabled(self::CACHE_ID)) {
            $this->cache->save($data, $cacheId, array(self::CACHE_TAG), $this->getCacheLifetime());
            return true;
        }
        return false;
    }

    /**
     * @return mixed
     */
    private function getCacheLifetime()
    {
        return $this->scopeConfig->getValue(
            self::XML_CONFIG_CACHE_LIFETIME,
            ScopeInterface::SCOPE_STORE
        );
    }
}
