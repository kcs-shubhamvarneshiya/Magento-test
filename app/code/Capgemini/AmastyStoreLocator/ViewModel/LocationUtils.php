<?php
/**
 * Capgemini_AmastyStoreLocator
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\AmastyStoreLocator\ViewModel;

use Amasty\Storelocator\Model\ConfigProvider;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * Location Utilytes
 */
class LocationUtils implements ArgumentInterface
{
    /**
     * @var \Magento\Framework\Escaper
     */
    protected $escaper;
    protected ConfigProvider $configProvider;

    /**
     * Constructor
     *
     * @param \Magento\Framework\Escaper $escaper
     */
    public function __construct(
        \Magento\Framework\Escaper $escaper,
        ConfigProvider $configProvider
    ) {
        $this->escaper = $escaper;
        $this->configProvider = $configProvider;
    }

    /**
     * Get location store hours
     *
     * @param \Amasty\Storelocator\Model\Location $location
     */
    public function getStoreHours(\Amasty\Storelocator\Model\Location $location)
    {
        $hours = $this->getCustomAttributeValue($location, 'store_hours');
        if ($hours) {
            $hours = str_replace(';', '<br/>', $this->escaper->escapeHtml($hours));
        }
        return $hours;
    }

    /**
     * Get appointment URL for the location
     *
     * @param \Amasty\Storelocator\Model\Location $location
     * @return string | null
     */
    public function getAppointmentUrl(\Amasty\Storelocator\Model\Location $location)
    {
        return $this->getCustomAttributeValue($location, 'appointment_url');
    }

    /**
     * Get message location attribute value
     *
     * @param \Amasty\Storelocator\Model\Location $location
     * @return string | null
     */
    public function getMessage(\Amasty\Storelocator\Model\Location $location)
    {
        return $this->getCustomAttributeValue($location, 'message');
    }

    /**
     * Get custom attribute value by code
     *
     * @param \Amasty\Storelocator\Model\Location $location
     * @param string $attributeCode
     * @return string | null
     */
    public function getCustomAttributeValue(\Amasty\Storelocator\Model\Location $location, string $attributeCode)
    {
        if (is_array($location->getAttributes()) && isset($location->getAttributes()[$attributeCode])) {
            return $location->getAttributes()[$attributeCode]['value'];
        }
        return null;
    }

    /**
     * Get configured location type for showroom.
     *
     * @return string|null
     */
    public function getShowroomTypeValue()
    {
        return $this->configProvider->getValue('location_types/showroom');
    }

    /**
     * Get configured location type for dealer.
     *
     * @return string|null
     */
    public function getDealerTypeValue()
    {
        return $this->configProvider->getValue('location_types/dealer');
    }
}
