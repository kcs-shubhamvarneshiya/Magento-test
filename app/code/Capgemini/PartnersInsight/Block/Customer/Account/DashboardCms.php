<?php
/**
 * Capgemini_PartnersInsight
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\PartnersInsight\Block\Customer\Account;

use Capgemini\PartnersInsight\Model\Config;
use Magento\Cms\Api\GetBlockByIdentifierInterface;
use Magento\Cms\Model\Template\FilterProvider;
use Magento\Framework\View\Element\Context;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Dashboard CMS block with a button "Visit Partners Insight" (button's label is configured in admin-panel).
 */
class DashboardCms extends \Magento\Cms\Block\BlockByIdentifier
{
    const CMS_BLOCK_IDENTIFIER = 'partners_insight_dashboard';
    /**
     * @var Config
     */
    protected $config;

    /**
     * Constructor
     *
     * @param GetBlockByIdentifierInterface $blockByIdentifier
     * @param StoreManagerInterface $storeManager
     * @param FilterProvider $filterProvider
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        GetBlockByIdentifierInterface $blockByIdentifier,
        StoreManagerInterface $storeManager,
        FilterProvider $filterProvider,
        Context $context,
        Config $config,
        array $data = []
    ) {
        parent::__construct($blockByIdentifier, $storeManager, $filterProvider, $context, $data);
        $this->setData('identifier', self::CMS_BLOCK_IDENTIFIER);
        $this->config = $config;
    }

    /**
     * {@inheritDoc}
     */
    public function _toHtml(): string
    {
        if ($this->config->isAllowed()) {
            $cmsContent = parent::_toHtml();
            $url = $this->getUrl('partners-insight/redirect');
            $buttonLinkText = $this->_escaper->escapeHtml($this->config->getPiConfig('button_link_text'));
            return '<div class="block block-partners-insight">'
                . $cmsContent
                . '<div class="box-actions">'
                . '<a class="action primary" target="_blank" href="' . $url . '">' . $buttonLinkText . '</a>'
                . '</div>'
                . '</div>';
        } else {
            return '';
        }
    }
}
