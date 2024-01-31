<?php

namespace Lyonscg\CircaLighting\Model\Config\Source;

use Magento\Store\Model\StoreManagerInterface;

class WebsiteSwitcher implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    public function __construct(StoreManagerInterface $storeManager)
    {
        $this->storeManager = $storeManager;
    }

    public function toOptionArray(): array
    {
        $websites = $this->storeManager->getWebsites(false, true);
        $optionArray = [];
        foreach ($websites as $code => $website) {
            $optionArray[] = [
                'value' => $code,
                'label' => $website->getName()
            ];
        }

        return $optionArray;
    }
}
