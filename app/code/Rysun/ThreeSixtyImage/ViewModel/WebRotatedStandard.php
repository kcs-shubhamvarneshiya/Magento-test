<?php

namespace Rysun\ThreeSixtyImage\ViewModel;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\ProductRepository;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Asset\Repository as AssetRepository;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;


class WebRotatedStandard implements ArgumentInterface
{
    const XML_PATH_ENABLED = 'Rysunstandard/general/enabled';
    const XML_PATH_VIEWER_SKIN = 'Rysunstandard/general/viewer_skin';
    const XML_PATH_USE_ANALYTICS = 'Rysunstandard/advanced/use_analytics';
    const XML_PATH_API_CALLBACK = 'Rysunstandard/advanced/api_callback';
    const XML_PATH_GRAPHICS_PATH = 'Rysunstandard/advanced/graphics_path';
    const XML_PATH_MASTER_CONFIG = 'Rysunstandard/advanced/master_config';
    const XML_PATH_LICENSE = 'Rysunstandard/advanced/license';
    const XML_PATH_POPUP_ICON = 'Rysunstandard/general/popup_icon';
    const XML_PATH_END_PLACEMENT = 'Rysunstandard/general/gallery_end_placement';
    const XML_PATH_MEDIA_URL = 'Rysunstandard/general/media_url_config';
    const ASSET_BASE_PATH = 'Rysun_ThreeSixtyImage::imagerotator';

    /** @var Registry */
    private $registry;

    /** @var ScopeConfigInterface */
    private $config;

    /** @var ProductRepository */
    private $productRepository;

    /** @var AssetRepository */
    private $assetRepository;

    /** @var StoreManagerInterface */
    private $storeManager;

    /**
     * @param Registry $registry
     * @param ScopeConfigInterface $config
     * @param ProductRepository $productRepository
     * @param AssetRepository $assetRepository
     */
    public function __construct(
        Registry $registry,
        ScopeConfigInterface $config,
        ProductRepository $productRepository,
        AssetRepository $assetRepository,
        StoreManagerInterface $storeManager
    ) {
        $this->registry = $registry;
        $this->config = $config;
        $this->productRepository = $productRepository;
        $this->assetRepository = $assetRepository;
        $this->storeManager = $storeManager;
    }

    /**
     * @return string|null
     */
    public function getWebRotatePath()
    {
        $product = $this->getCurrentProduct();

        if ($product) {
            return ltrim($product->getData('pdp_360') ?? '', '/');
        }

        return null;
    }

    /**
     * @return ProductInterface | null
     */
    public function getCurrentProduct()
    {
        return $this->registry->registry('product');
    }

    /**
     * @return |null
     */
    public function getWebRotateRootUrl()
    {
        $product = $this->getCurrentProduct();

        if ($product) {
            return $product->getData('pdp_360');
        }

        return null;
    }

    /**
     * @return mixed
     */
    public function getGraphicsPathUrl()
    {
        $graphicsPath = $this->config->getValue(self::XML_PATH_GRAPHICS_PATH, ScopeInterface::SCOPE_STORE);
        if (!empty($graphicsPath)) {
            return $graphicsPath;
        }

        return $this->assetRepository->getUrl('Rysun_ThreeSixtyImage::graphics/');
    }

    /**
     * @return mixed
     */
    public function getViewerSkin()
    {
        return $this->config->getValue(self::XML_PATH_VIEWER_SKIN, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->config->isSetFlag(self::XML_PATH_ENABLED, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function getViewerSkinUrl()
    {
        return $this->assetRepository->getUrl(self::ASSET_BASE_PATH . '/html/css/' . $this->getViewerSkin() . '.css');
    }

    /**
     * @return bool
     */
    public function isEndPlacement()
    {
        return $this->config->isSetFlag(self::XML_PATH_END_PLACEMENT, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return bool
     */
    public function isUseAnalytics()
    {
        return $this->config->isSetFlag(self::XML_PATH_USE_ANALYTICS, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return mixed
     */
    public function getApiCallback()
    {
        return $this->config->getValue(self::XML_PATH_API_CALLBACK, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return mixed
     */
    public function getLicense()
    {
        $licPath = $this->config->getValue(self::XML_PATH_LICENSE, ScopeInterface::SCOPE_STORE);
        if (!empty($licPath)) {
            return $licPath;
        }

        return $this->assetRepository->getUrl(self::ASSET_BASE_PATH . '/license.lic');
    }

    /**
     * @return mixed
     */
    public function getPopupIconUrl()
    {
        $value = $this->config->getValue(self::XML_PATH_POPUP_ICON, ScopeInterface::SCOPE_STORE);

        if (empty($value)) {
            return $this->assetRepository->getUrl('Rysun_ThreeSixtyImage::360thumb.svg');
        }

        return $value;
    }

    /**
     * @return string|null
     * @throws NoSuchEntityException
     */
    public function getSwatches()
    {
        $configProduct = $this->getCurrentProduct();

        if (!$configProduct || $configProduct->getTypeId() != Configurable::TYPE_CODE) {
            return null;
        }

        $swatches = (object)[];
        $children = $configProduct->getTypeInstance()->getUsedProducts($configProduct);
        $masterConfig = $this->getMasterConfigUrl();
        $hasSwatches = false;

        $configBaseUrl = $this->getBaseUrl();
        if ($this->getUseMediaUrlConfig() === true) {
            $configBaseUrl = $this->getMediaUrl();
        }

        foreach ($children as $child) {
            $fetched = $this->productRepository->getById($child->getId());
            $configRoot = $fetched->getData('pdp_360');
            $configUrl = ltrim($fetched->getData('pdp_360') ?? '', '/');

            if (!$configUrl && $masterConfig && $configRoot) {
                $configUrl = $masterConfig;
            }

            if ($configUrl) {
                $hasSwatches = true;
                $swatches->{$child->getId()} = [
                    'confFileURL' => strpos($configUrl, 'https://') === 0 ? $configUrl : $configBaseUrl .'/media/docs/360/'.$configUrl,
                    'rootPath' => $configRoot
                ];
            }
        }

        return $hasSwatches ? json_encode($swatches, JSON_UNESCAPED_SLASHES) : null;
    }

    /**
     * @return mixed
     */
    public function getMasterConfigUrl()
    {
        return $this->config->getValue(self::XML_PATH_MASTER_CONFIG, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     * @throws NoSuchEntityException
     */
    public function getBaseUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl();
    }

    /**
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getMediaUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
    }

    /**
     * @return bool
     */
    public function getUseMediaUrlConfig()
    {
        return (bool) $this->config->getValue(self::XML_PATH_MEDIA_URL, ScopeInterface::SCOPE_STORE);
    }

}
