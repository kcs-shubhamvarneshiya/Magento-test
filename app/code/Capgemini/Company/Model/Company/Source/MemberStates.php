<?php

namespace Capgemini\Company\Model\Company\Source;

use Capgemini\Company\Helper\Data as Helper;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Directory\Model\ResourceModel\Country\CollectionFactory as CountryCollectionFactory;

class MemberStates implements OptionSourceInterface
{

    /**
     * @var Helper
     */
    private $helper;

    /**
     * @var CountryCollectionFactory
     */
    private $countryCollectionFactory;

    /**
     * @param Helper $helper
     * @param CountryCollectionFactory $countryCollectionFactory
     */
    public function __construct(
        Helper $helper,
        CountryCollectionFactory $countryCollectionFactory
    ) {
        $this->helper = $helper;
        $this->countryCollectionFactory = $countryCollectionFactory;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $countries = $this->countryCollectionFactory->create()
            ->addFieldToFilter('country_id', ['in' => $this->helper->getMemberStateCountries()])
            ->toOptionArray();

        $options = [];
        foreach ($countries as $country) {
            if (empty($country['value'])) {
                $options[] = [
                    'value' => '',
                    'label' => 'Select...'
                ];
                continue;
            }
            $options[] = [
                'value' => $country['value'] . " - " . $country['label'],
                'label' => $country['value'] . " - " . $country['label']
            ];
        }
        return $options;
    }
}
