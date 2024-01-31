<?php

namespace Capgemini\BloomreachThematic\Model\Layer;

use Amasty\Base\Model\MagentoVersion;
use Amasty\Shopby\Helper\Config;
use Amasty\Shopby\Helper\FilterSetting;
use Amasty\Shopby\Model\Layer\FilterList as Orig;
use Amasty\Shopby\Model\Request;
use Amasty\ShopbyBase\Model\FilterSetting\FilterResolver;
use Capgemini\BloomreachThematic\Model\Layer\Filter\Attribute as ThematicAttribute;
use Capgemini\BloomreachThematic\Model\Layer\Filter\Price as ThematicPrice;
use Magento\Catalog\Model\Layer\FilterableAttributeListInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\LayoutInterface;
use Capgemini\BloomreachThematic\Helper\Data as ModuleHelper;

class FilterList extends Orig
{
    public function __construct(
        ObjectManagerInterface $objectManager,
        FilterableAttributeListInterface $filterableAttributes,
        MagentoVersion $magentoVersion,
        FilterSetting $filterSettingHelper,
        Http $request,
        Registry $registry,
        Request $shopbyRequest,
        Config $config,
        LayoutInterface $layout,
        FilterResolver $filterResolver,
        ModuleHelper $moduleHelper,
        array $filters = [],
        $place = self::PLACE_SIDEBAR
    ) {
        if ($moduleHelper->getIsThematicRequest()) {
            if (isset($filters['attribute'])){
                $filters['attribute'] = ThematicAttribute::class;
            }

            if (isset($filters['price'])){
                $filters['price'] = ThematicPrice::class;
            }
        }

        parent::__construct(
            $objectManager,
            $filterableAttributes,
            $magentoVersion,
            $filterSettingHelper,
            $request,
            $registry,
            $shopbyRequest,
            $config,
            $layout,
            $filterResolver,
            $filters,
            $place
        );
    }
}
