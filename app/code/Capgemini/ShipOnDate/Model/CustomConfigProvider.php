<?php
/**
 * Capgemini_ShipOnDate
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\ShipOnDate\Model;

use Capgemini\ShipOnDate\Helper\Data;
use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;

class CustomConfigProvider implements ConfigProviderInterface
{
    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param Data $helper
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Data $helper,
        StoreManagerInterface $storeManager
    ) {
        $this->helper = $helper;
        $this->storeManager = $storeManager;
    }

    /**
     * @return array
     * @throws NoSuchEntityException
     */
    public function getConfig()
    {
        $config = [];
        $storeId = (int) $this->storeManager->getStore()->getId();
        $config['shipping']['delivery_date_method_code'] = $this->helper->getShippingMethodCode();
        $config['shipping']['delivery_date_first'] = $this->helper->getFirstAvailableDate($storeId);
        $config['shipping']['delivery_date_last'] = $this->helper->getLastAvailableDate($storeId);
        $config['shipping']['delivery_date_allowed_days'] = $this->helper->getAllowedDays($storeId);

        return $config;
    }
}
