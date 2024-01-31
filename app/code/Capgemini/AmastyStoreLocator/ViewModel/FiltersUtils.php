<?php
/**
 * Capgemini_AmastyStoreLocator
 *
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\AmastyStoreLocator\ViewModel;

use Amasty\Storelocator\Model\ConfigProvider;
use Magento\Framework\Escaper;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class FiltersUtils implements ArgumentInterface
{
    /**
     * @var Escaper
     */
    protected Escaper $escaper;

    /**
     * @var ConfigProvider
     */
    protected ConfigProvider $configProvider;

    /**
     * Constructor
     *
     * @param Escaper $escaper
     * @param ConfigProvider $configProvider
     */
    public function __construct(
        Escaper $escaper,
        ConfigProvider $configProvider
    ) {
        $this->escaper = $escaper;
        $this->configProvider = $configProvider;
    }

    /**
     * @return bool
     */
    public function isFiltersEnabled(): bool
    {
        return (bool)$this->configProvider->getValue('general/filters_enabled');
    }
}
