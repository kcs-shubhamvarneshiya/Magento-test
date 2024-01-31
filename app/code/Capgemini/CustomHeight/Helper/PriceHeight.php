<?php

namespace Capgemini\CustomHeight\Helper;
use Magento\Framework\Serialize\SerializerInterface as Serializer;
use Magento\ConfigurableProduct\Api\LinkManagementInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class PriceHeight
 * @author Yaroslav Protsko <yaroslav.protsko@capgemini.com>
 * @copyright 2021 Capgemini, Inc. (www.capgemini.com)
 */
class PriceHeight extends \Magento\Framework\App\Helper\AbstractHelper
{
    const MAX_HEIGH_CONFIG = 'custom_height/general/custom_height';

    const INCREMENT_CONFIG = 'custom_height/general/height_increment';

    const HEIGT_PRICING_CONFIG = 'custom_height/general/custom_height_pricing';

    const ROOM_CONFIG = 'custom_height/custom_calculator/room_configuration';

    const STATIC_NOTE = 'custom_height/general/note';

    const AVAILABILITY_MESSAGE = 'custom_height/general/availability_message';

    const CUT_DOWN_COST = 'custom_height/general/cut_down_cost';

    const CUT_DOWN_PRICE = 'custom_height/general/cut_down_price';

    /**
     * @var mixed
     */
    protected $maxHeight;

    /**
     * @var array|bool|float|int|string|null
     */
    protected $pricing = [];

    /**
     * @var array|bool|float|int|string|null
     */
    protected $increment;

    /**
     * @var
     */
    protected $cutDownPrice;

    /**
     * @var Serializer
     */
    protected $serializer;

    protected $heightCostArray;

    /**
     * @var LinkManagementInterface
     */
    protected $linkManagement;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var array
     */
    public $heightPricingForAllProducts = [];

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var int
     */
    private $rodOptionId = null;

    /**
     * PriceHeight constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param Serializer $serializer
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        Serializer $serializer,
        LinkManagementInterface $linkManagement,
        ProductRepositoryInterface $productRepository,
        StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
        $this->linkManagement = $linkManagement;
        $this->serializer= $serializer;
        $this->productRepository = $productRepository;
        $this->cutDownPrice = $this->scopeConfig->getValue(self::CUT_DOWN_PRICE,
            \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE);
        $this->maxHeight = $this->scopeConfig->getValue(self::MAX_HEIGH_CONFIG,
            \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE);
        $this->increment = $this->serializer->unserialize($this->scopeConfig->getValue(self::INCREMENT_CONFIG,
            \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE));
        $this->preparePricingArray();
        $this->storeManager = $storeManager;
    }

    /**
     * @param float $heightMin
     * @param float $oaHeight
     * @param int $rodQty
     * @return array
     */
    public function getHeightPricing(float $heightMin, float $oaHeight, int $rodQty) : array
    {
        $pricing = [];
        asort($this->pricing);
        $priceHeights = array_keys($this->pricing);
        $maxOaHeight = min($this->maxHeight, $oaHeight + end($priceHeights));
        $maxAddedHeight = max($priceHeights);
        $previoisHeigh = $oaHeight;
        for ($height = $heightMin; $height <= $maxOaHeight; $height += $this->increment) {
            // Do not price for an added height greater than configured
            $addedHeight = $height - $oaHeight;
            if ($addedHeight > $maxAddedHeight) {
                break;
            }
            if ($addedHeight < 0) {
                $price = $this->cutDownPrice * $rodQty;
            } else {
                $price = $this->getPrice($addedHeight) * $rodQty;
            }
            $label = "Height " . $height . '" +$' . $price;

            if ($previoisHeigh < $oaHeight && $oaHeight <= $height) {
                $price = 0;
                $label = "Standard " . $oaHeight . '" +$0';
            }

            $previoisHeigh = $height;

            $pricing[round($height, 0)] = [
                'height' => $height,
                'price' => $price,
                'addedHeight' => $addedHeight,
                'label' => $label
            ];
        }
        krsort($pricing);

        return $pricing;
    }

    /**
     * @param float $addedHeight
     * @return float
     */
    private function getPrice(float $addedHeight) : float
    {
        // reverse sort pricing by length
        krsort($this->pricing);

        // Get price for shortest length
        $addedPrice = array_values($this->pricing)[0];

        foreach ($this->pricing as $length => $price) {
            if ($addedHeight <= $length) {
                $addedPrice = $price;
            }
        }
        return $addedPrice;
    }

    protected function preparePricingArray()
    {
       $pricingFromDb =  $this->serializer->unserialize($this->scopeConfig->getValue(self::HEIGT_PRICING_CONFIG,
            \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE));
       foreach ($pricingFromDb as $pricing) {
            $this->pricing[$pricing['length']] = $pricing['price'];
            $this->heightCostArray[$pricing['length']] = $pricing['cost'];
       }
    }

    /**
     * @return array|bool|float|int|string|null
     */
    public function getRoomConfiguration()
    {
       return $this->serializer->unserialize($this->scopeConfig->getValue(self::ROOM_CONFIG,
            \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE));
    }

    /**
     * @return mixed
     */
    public function getJsonRoomConfiguration()
    {
        $mediaUrl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
        $roomConfiguration = $this->serializer->unserialize($this->scopeConfig->getValue(self::ROOM_CONFIG,
            \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE));
        $reformattedRoomConfig =[];
        foreach ($roomConfiguration as $roomConfig) {
             $roomName = str_replace('  ',' ', $roomConfig['room_name']);
             $roomName = str_replace(' ', '_', $roomName);
             $roomName = str_replace('/', '',  $roomName);
             $roomName = str_replace('__', '_', $roomName);
             $roomName = strtolower($roomName);
             $reformattedRoomConfig[$roomName] = [
                 'room_name' => $roomConfig['room_name'],
                 'measure_1' => $roomConfig['measure_1'],
                 'measure_2' => $roomConfig['measure_2'],
                 'measure_3' => $roomConfig['measure_3'],
                 'tip' => $roomConfig['tip'],
                 'tip_image' => !empty($roomConfig['tip_image']) ? $mediaUrl . $roomConfig['tip_image'] : null
             ];
        }

        return $this->serializer->serialize($reformattedRoomConfig);
    }

    public function getMaxHeight()
    {
        return $this->maxHeight;
    }

    /**
     * @param $additionalHeight
     * @return false|mixed
     */
    public function getCostByHeight($additionalHeight)
    {
        if (!isset($this->heightCostArray[(string) $additionalHeight])){
            $this->preparePricingArray();
        }
        if (count($this->heightCostArray) > 0) {
            ksort($this->heightCostArray);
            foreach ($this->heightCostArray as $length => $cost) {
                if ($additionalHeight <= $length) {
                    return $cost;
                }
            }
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function getStaticNoteText()
    {
        return $this->scopeConfig->getValue(self::STATIC_NOTE,
            \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE);
    }

    /**
     * @return mixed
     */
    public function getAvailabilityMessage()
    {
        return  $this->scopeConfig->getValue(self::AVAILABILITY_MESSAGE,
            \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE);
    }

    /**
     * @param $product
     * @return bool
     */
    public function isCustomHeightDisplayed($product){
        if (is_null($this->rodOptionId)) {
            foreach ($product->getResource()->getAttribute('mounting')->getOptions() as $option) {
                if ($option->getLabel() == 'Rod') {
                    $this->rodOptionId = $option->getValue();
                    break;
                }
            }
        }

        if (!is_null($this->rodOptionId)) {
            $childrenIds = $product->getTypeInstance()->getChildrenIds($product->getId());
            /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $collection */
            $collection = $product->getCollection();
            return $collection
                    ->addAttributeToFilter('mounting', $this->rodOptionId)
                    ->addAttributeToFilter('entity_id', ['in' => $childrenIds])
                    ->count() > 0;
        }

        return false;
    }

    /**
     * @param $product
     * @return array
     */
    public function getAttributeFromChilds($product)
    {
        $childAttributes = [];
        $attributesFromChilds = [];
        $childProducts = $this->linkManagement->getChildren($product->getSku());
        foreach ($childProducts as $childProduct){
            $childProduct = $this->productRepository->get($childProduct->getSku());
            $childAttributes['heightMin'] =  $childProduct->getMinheight();
            $childAttributes['oaHeight'] =  $childProduct->getOaheight();
            $childAttributes['rodQty'] =  $childProduct->getRodqty();
            $attributesFromChilds[$childProduct->getSku()]  = $childAttributes;

        }

        return $attributesFromChilds;
    }

    public function serializeValue($value) {
        return $this->serializer->serialize($value);
    }

    /**
     * @param $product
     * @return array|mixed
     */
    public function getHeightPricingForAllProducts($product)
    {
        if (count($this->heightPricingForAllProducts) > 0) {
            $firstKey = array_key_first($this->heightPricingForAllProducts);
            return $this->heightPricingForAllProducts[$firstKey];
        }
        $childAttributes = $this->getAttributeFromChilds($product);

        if (count($childAttributes) == 0 ) {
            return [];
        }

        foreach ($childAttributes as $childSku => $childAttribute) {
            if (empty($childAttribute['heightMin']) || empty($childAttribute['oaHeight'])
                || empty($childAttribute['rodQty'])) {
                return [];
            }
        }
        foreach ($childAttributes as $childSku => $childAttribute) {
            $this->heightPricingForAllProducts[$childSku] = $this->getHeightPricing((float)$childAttribute['heightMin'], (float)$childAttribute['oaHeight'],
                (int)$childAttribute['rodQty']);
        }
        $firstKey = array_key_first($this->heightPricingForAllProducts);

        return $this->heightPricingForAllProducts[$firstKey];
    }
}
