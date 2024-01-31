<?php

namespace Lyonscg\Affirm\Helper;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Lyonscg\Affirm\Model\Rule\RuleFactory;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Model\StoreManagerInterfaceFactory;

class Rule extends AbstractHelper
{
    const XML_CONFIG_PATH_SERIALIZED_EXCLUDE_CONDITIONS = 'payment/affirm_gateway/exclude_conditions_wrapper/serialized_exclude_conditions';
    const XML_CONFIG_PATH_MATCHING_PRODUCT_IDS = 'payment/affirm_gateway/exclude_conditions_wrapper/matching_product_ids';

    /**
     * @var RuleFactory
     */
    private $ruleFactory;

    /**
     * @var Json
     */
    private $serializer;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var \Lyonscg\Affirm\Model\Rule\Rule
     */
    private $ruleFromPost;

    /**
     * @var \Lyonscg\Affirm\Model\Rule\Rule
     */
    private $ruleFromSerialised;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    private $checkoutSession;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var array[int]
     */
    private $matchingProductIdsFromConfig;

    /**
     * @var bool
     */
    private $isNeedToHideCached;

    public function __construct(
        Context $context, RuleFactory $ruleFactory,
        Json $serializer,
        StoreManagerInterface $storeManager,
        Registry $registry,
        \Magento\Checkout\Model\Session $checkoutSession,
        ProductRepositoryInterface $productRepository
    ) {
        parent::__construct($context);
        $this->ruleFactory = $ruleFactory;
        $this->serializer = $serializer;
        $this->storeManager = $storeManager;
        $this->registry = $registry;
        $this->checkoutSession = $checkoutSession;
        $this->productRepository = $productRepository;
    }

    /**
     * @return bool|string
     */
    public function prepareSerializedConditions()
    {
        $model = $this->getRuleFromPostData();

        if ($model->getConditions()) {

            return $this->serializer->serialize($model->getConditions()->asArray());
        }

        return '';
    }

    /**
     * @return bool|string
     */
    public function prepareSerializedMatchingProductIds()
    {
        $model = $this->getRuleFromPostData();

        $productIds = [];
        if ($model->getConditions()->getConditions()) {
            $model->setData('website_ids', $this->retrieveWebsiteIds());
            $productIds = $model->getMatchingProductIds();
            $this->matchingProductIdsFromConfig = null;
        }

        return $this->serializer->serialize(array_keys($productIds));
    }

    public function getMatchingProductIdsFromConfig()
    {
        if (!$this->matchingProductIdsFromConfig) {
            $this->matchingProductIdsFromConfig = $this->loadMatchingProductIdsFromConfig();
        }

        return $this->matchingProductIdsFromConfig;
    }

    public function registerRuleFromSerialisedData()
    {
        $model = $this->getRuleFromSerializedData() ?? $this->ruleFactory->create();

        if ($model->getConditions()) {
            $model->setData('website_ids', $this->retrieveWebsiteIds());
        }

        $this->registry->register('current_promo_catalog_rule', $model);
    }

    /**
     * @return int|int[]|mixed|string[]|null
     */
    public function retrieveWebsiteIds()
    {
        if ($websiteId = $this->_request->getParam('website')) {

            return $websiteId;
        }

        if (!$storeId = $this->_request->getParam('store')) {

            return array_keys($this->storeManager->getWebsites());
        }

        try {
            $websiteId = $this->storeManager->getStore($storeId)->getWebsiteId();
        } catch (\Exception $exception) {
            $websiteId = null;
        }

        return $websiteId;
    }

    /**
     * @return bool
     */
    public function isNeedToHide()
    {
        if (!$this->isNeedToHideCached) {
            $this->isNeedToHideCached = $this->shouldHideOfPage() || $this->shouldHideOfCart();
        }

        return $this->isNeedToHideCached;
    }

    /**
     * @return \Lyonscg\Affirm\Model\Rule\Rule|null
     */
    private function getRuleFromPostData(): ?\Lyonscg\Affirm\Model\Rule\Rule
    {
        if (!$this->_request instanceof Http) {
            $this->ruleFromPost = null;
            $this->ruleFromSerialised = null;

            return $this->ruleFromPost;
        }

        if (!$this->ruleFromPost) {
            $conditions = $this->_request->getPost()->get('rule');
            if ($conditions) {
                $this->ruleFromPost = $this->ruleFactory->create();
                $this->ruleFromPost->loadPost($conditions);
            }
        }

        return $this->ruleFromPost;
    }

    /**
     * @return \Lyonscg\Affirm\Model\Rule\Rule|null
     */
    private function getRuleFromSerializedData(): ?\Lyonscg\Affirm\Model\Rule\Rule
    {
        if (!$this->_request instanceof Http) {
            $this->ruleFromPost = null;
            $this->ruleFromSerialised = null;

            return $this->ruleFromSerialised;
        }

        if (!$this->ruleFromSerialised) {
            $websiteIds = $this->retrieveWebsiteIds();

            if (is_array($websiteIds)) {
                $conditionsSerialized = $this->scopeConfig->getValue(self::XML_CONFIG_PATH_SERIALIZED_EXCLUDE_CONDITIONS);
            } else {
                $conditionsSerialized = $this->scopeConfig->getValue(
                    self::XML_CONFIG_PATH_SERIALIZED_EXCLUDE_CONDITIONS,
                    'website',
                    $websiteIds
                );
            }

            if ($conditionsSerialized) {
                $this->ruleFromSerialised = $this->ruleFactory->create();
                $this->ruleFromSerialised->setConditionsSerialized($conditionsSerialized);
            }
        }

        return $this->ruleFromSerialised;
    }

    /**
     * @return bool
     */
    private function shouldHideOfCart()
    {
        try {
            $quote = $this->checkoutSession->getQuote();
        } catch (\Exception $exception) {

            return false;
        }

        $excluding = $this->getMatchingProductIdsFromConfig();

        if (empty($excluding)) {

            return false;
        }

        if (!$items = $quote->getItems()) {

            return false;
        }

        foreach ($items as $item) {
            $itemSimpleProdSku = $item ->getSku();
            try {
                $itemSimpleProdId = $this->productRepository->get($itemSimpleProdSku)->getId();
            } catch (NoSuchEntityException $e) {

                continue;
            }

            if (in_array($itemSimpleProdId, $excluding)) {

                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    private function shouldHideOfPage()
    {
        if (!$this->_request instanceof Http) {

            return false;
        }

        $fullActionName = $this->_request->getFullActionName();

        if ($fullActionName !== 'catalog_product_view') {

            return false;
        }

        $excluding = $this->getMatchingProductIdsFromConfig();
        $productId = $this->_request->getParam('id');
        try {
            $product = $this->productRepository->getById($productId);
        } catch (\Exception $exception) {

            return false;
        }
        $typeInstance = $product->getTypeInstance();
        $idsToCheck = method_exists($typeInstance, 'getChildrenIds') ? $typeInstance->getChildrenIds($productId) : [];
        $idsToCheck = $this->refineArray($idsToCheck);
        array_push($idsToCheck, $productId);

        return !!array_intersect($idsToCheck, $excluding);
    }

    /**
     * @return array|bool|float|int|mixed|string
     */
    private function loadMatchingProductIdsFromConfig()
    {
        $productIds = $this->scopeConfig->getValue(self::XML_CONFIG_PATH_MATCHING_PRODUCT_IDS, 'websites');
        $productIds = $productIds ? $this->serializer->unserialize($productIds) : [];

        return $productIds ?: [];
    }

    /**
     * Retrieves end values of complex array.
     *
     * @param array $idsArray
     * @return array
     */
    private function refineArray(array $idsArray): array
    {
        $endArray = [];
        foreach ($idsArray as $item) {
            if (is_array($item)) {
                $tempArray = $this->refineArray($item);
            } else {
                $tempArray = [$item];
            }
            $endArray = array_merge($endArray, $tempArray);
        }

        return $endArray ?? [];
    }
}
