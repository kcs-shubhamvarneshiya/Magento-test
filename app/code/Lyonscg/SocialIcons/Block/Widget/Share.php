<?php

namespace Lyonscg\SocialIcons\Block\Widget;

use Magento\Catalog\Helper\Image;
use Magento\Catalog\Helper\Output;
use Magento\Catalog\Helper\Product as ProductHelper;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\SendFriend\Helper\Data as SendFriendHelper;
use Magento\Widget\Block\BlockInterface;

class Share extends Template implements BlockInterface
{
    /**
     * Widget template
     * @var string
     */
    protected $_template = "Lyonscg_SocialIcons::widget/share.phtml";

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var ProductHelper
     */
    protected $productHelper;

    /**
     * @var SendFriendHelper
     */
    protected $sendFriendHelper;

    /**
     * @var Output
     */
    protected $ouptutHelper;

    /**
     * @var Image
     */
    protected $imageHelper;

    /**
     * @var string[]
     */
    protected $showUrls = [];

    /**
     * @var \Magento\Catalog\Model\Product|null
     */
    protected $product = null;

    /**
     * @var string
     */
    protected $productName = '';

    /**
     * @var string
     */
    protected $productImage = '';

    /**
     * @var string
     */
    protected $productUrl = '';

    /**
     * Share constructor.
     * @param Context $context
     * @param Registry $registry
     * @param ProductHelper $productHelper
     * @param SendFriendHelper $sendFriendHelper
     * @param Output $outputHelper
     * @param Image $imageHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ProductHelper $productHelper,
        SendFriendHelper $sendFriendHelper,
        Output $outputHelper,
        Image $imageHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->registry = $registry;
        $this->productHelper = $productHelper;
        $this->sendFriendHelper = $sendFriendHelper;
        $this->ouptutHelper = $outputHelper;
        $this->imageHelper = $imageHelper;
    }

    /**
     * @return \Magento\Catalog\Model\Product|null
     */
    protected function _getProduct()
    {
        if ($this->product === null) {
            $this->product = $this->registry->registry('current_product');
        }
        return $this->product;
    }

    /**
     * @return array|string[]
     */
    public function getShowUrls()
    {
        if (empty($this->showUrls)) {
            $this->showUrls = explode(',', $this->getData('show_urls'));
        }
        return $this->showUrls;
    }

    /**
     * @param $which
     * @return bool
     */
    public function shouldShow($which)
    {
        if ($which === 'email' && !$this->sendFriendHelper->isEnabled()) {
            return false;
        }
        return in_array($which, $this->getshowUrls());
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _getProductName()
    {
        if (empty($this->productName)) {
            $product = $this->_getProduct();
            if (!$product) {
                return '';
            }
            $this->productName = $this->ouptutHelper->productAttribute(
                $product,
                $product->getName(),
                'name'
            );
        }
        return $this->productName;
    }

    /**
     * @return string
     */
    protected function _getProductImage()
    {
        if (empty($this->productImage)) {
            $product = $this->_getProduct();
            if (!$product) {
                return '';
            }
            $this->productImage = $this->imageHelper->init($product, 'product_base_image')->getUrl();
        }
        return $this->productImage;
    }

    /**
     * @return string
     */
    protected function _getProductUrl()
    {
        if (empty($this->productUrl)) {
            $product = $this->_getProduct();
            if (!$product) {
                return '';
            }
            $this->productUrl = $product->getProductUrl();
        }
        return $this->productUrl;
    }

    /**
     * @param string $which
     * @return string
     */
    public function getShareUrl($which)
    {
        return $this->escapeUrl($this->_getShareUrl($which));
    }

    /**
     * @param string $which
     * @return string
     */
    protected function _getShareUrl($which)
    {
        if (!$this->_getProduct()) {
            return '';
        }

        $product = $this->_getProduct();
        $url = $this->_getProductUrl();
        $name = $this->_getProductName();
        $image = $this->_getProductImage();

        switch ($which) {
            case 'facebook':
                return sprintf(
                    'https://www.facebook.com/sharer/sharer.php?u=%s&p[title]=%s&p[images][0]=%s',
                    $url,
                    $name,
                    $image
                );
            case 'pinterest':
                sprintf(
                    '//www.pinterest.com/pin/create/button/?url=%s&media=%s&description=%s',
                    $url,
                    $image,
                    $name
                );
            case 'twitter':
                return sprintf(
                    'https://twitter.com/share?url=%s&text=%s',
                    $url,
                    $name
                );
            case 'email':
                return $this->productHelper->getEmailToFriendUrl($product);
            default:
                return '';
        }
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getDesignStudioData()
    {
        $product = $this->_getProduct();
        if (!$product) {
            return [];
        }

        $shortDesc = $this->ouptutHelper->productAttribute(
            $product,
            $product->getShortDescription(),
            'short_description'
        );

        $desc = $this->ouptutHelper->productAttribute(
            $product,
            $product->getDescription(),
            'description'
        );

        $result = [
            'sku' => $product->getSku(),
            'vendor' => '',
            'short_desc' => $shortDesc,
            'desc' => $desc,
            'image1' => '',
            'image2' => '',
            'image3' => '',
            'image4' => '',
            'image5' => ''
        ];
        $idx = 1;
        foreach ($product->getMediaGalleryImages() as $galleryImage) {
            $result['image' . $idx] = $galleryImage->getUrl();
            $idx++;
        }
        return $result;
    }
}
