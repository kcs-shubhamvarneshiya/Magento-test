<?php
/**
 * Capgemini_AmastyStoreLocator
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\AmastyStoreLocator\Block;

use Amasty\Storelocator\Model\ConfigProvider;
use Amasty\Storelocator\Model\ResourceModel\Location\Collection;
use Amasty\Storelocator\Model\ResourceModel\Location\CollectionFactory;
use Capgemini\AmastyStoreLocator\Model\LocationType;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

/**
 * Showrooms list block
 */
class ShowroomList extends Template
{
    /**
     * @var Collection
     */
    protected $showrooms;
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;
    /**
     * @var LocationType
     */
    protected $locationType;
    /**
     * @var ConfigProvider
     */
    protected $configProvider;

    /**
     * @param Context $context
     * @param CollectionFactory $collectionFactory
     * @param LocationType $locationType
     * @param ConfigProvider $configProvider
     * @param array $data
     */
    public function __construct(
        Context                 $context,
        CollectionFactory       $collectionFactory,
        LocationType            $locationType,
        ConfigProvider          $configProvider,
        array                   $data = []
    ) {
        parent::__construct($context, $data);
        $this->collectionFactory = $collectionFactory;
        $this->locationType = $locationType;
        $this->configProvider = $configProvider;
    }

    /**
     * Get showrooms collection
     *
     * @return Collection
     */
    public function getShowrooms()
    {
        if (!$this->showrooms) {
            $this->initCollection();
        }
        return $this->showrooms;
    }

    /**
     * Initialize showrooms collection
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function initCollection()
    {
        $this->showrooms = $this->collectionFactory->create();
        $this->showrooms->applyDefaultFilters();
        $this->showrooms->joinScheduleTable();
        $this->showrooms->joinMainImage();
        $directoryCountryRegion = $this->showrooms->getConnection()->getTableName('directory_country_region');
        $this->showrooms->getSelect()->joinLeft(['region' => $directoryCountryRegion], 'region.region_id = main_table.state', ['state_code' => 'code']);
        $this->showrooms->applyAttributeFilters($this->getShowroomFilter());
        $this->showrooms->addOrder('state_code', 'ASC');
        $this->showrooms->addOrder('city', 'ASC');
        $this->showrooms->getLocationData();
    }

    /**
     * Prepare filter by Showroom type
     *
     * @return array[]
     */
    protected function getShowroomFilter()
    {
        $showroomValue = $this->configProvider->getValue('location_types/showroom');
        return [$this->locationType->getAttributeId() => [$this->locationType->getOptionId($showroomValue)]];
    }

    /**
     * Get URL to the location page
     *
     * @param \Amasty\Storelocator\Model\Location $location
     */
    public function getLocationUrl(\Amasty\Storelocator\Model\Location $location)
    {
        if ($location->getUrlKey()) {
            return $this->getUrl($this->configProvider->getUrl() . '/' . $location->getUrlKey());
        }
        return null;
    }
}
