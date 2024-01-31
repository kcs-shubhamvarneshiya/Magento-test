<?php
namespace Rysun\Olapicblock\Block\Widget;
use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;
use Magento\Store\Model\ScopeInterface;
class OlapicApi extends Template implements BlockInterface
{
    protected $_template = "widget/olapicapi.phtml";

    const XML_PATH_OLAPIC_ENABLED = 'rysun_olapicblock/general/enabled';
    const XML_PATH_OLAPIC_SCRIPT_URL = 'rysun_olapicblock/general/script_url';
    const XML_PATH_OLAPIC_INSTANCE = 'rysun_olapicblock/general/instance_value';
    const XML_PATH_OLAPIC_APIKEY = 'rysun_olapicblock/general/apikey';

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * Olapic constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */

     public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->registry = $registry;
    }

     /**
     * @return \Magento\Catalog\Api\Data\ProductInterface|null
     */
    public function getProduct()
    {
        if (!$this->hasData('product')) {
            $this->setData('product', $this->registry->registry('product'));
        }
        return $this->getData('product');
    }

     /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->_scopeConfig->isSetFlag(
            self::XML_PATH_OLAPIC_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

     /**
     * @return mixed
     */
    public function getScriptUrl()
    {
        
        return $this->_scopeConfig->getValue(
            self::XML_PATH_OLAPIC_SCRIPT_URL,
            ScopeInterface::SCOPE_STORE
        );
    }

     /**
     * @return mixed
     */
    public function getInstance()
    {
        return $this->_scopeConfig->getValue(
            self::XML_PATH_OLAPIC_INSTANCE,
            ScopeInterface::SCOPE_STORE
        );
    }

     /**
     * @return mixed
     */
    public function getApikey()
    {
        return $this->_scopeConfig->getValue(
            self::XML_PATH_OLAPIC_APIKEY,
            ScopeInterface::SCOPE_STORE
        );
    }

     /**
     * @return string
     */
    public function getTags()
    {
        if (!$this->getProduct()) {
            return '';
        } else {
            $product = $this->getProduct();
            if ($product->getData('olapic_id')) {
                return $product->getData('olapic_id');
            } else {
                return $this->getProduct()->getSku();
            }
        }
    }

     /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getSocialLinksHtml()
    {
        /** @var \Lyonscg\SocialIcons\Block\Widget\Share $socialLinksBlock */
        try {
            $socialLinksBlock = $this->getLayout()
                ->createBlock(\Lyonscg\SocialIcons\Block\Widget\Share::class)
                ->setShowUrls('facebook,pinterest,twitter,email,design');
            return $socialLinksBlock->toHtml();
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->_logger->error($e);
            return '';
        }
    }

    // public function getStreamId()
    // {

    // }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        if (!$this->isEnabled()) {
            return '';
        } else {
            return parent::_toHtml();
        }
    }
}
