<?php
/**
 * Capgemini_PartnersInsight
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\PartnersInsight\Block\Customer;

use Capgemini\PartnersInsight\Model\Config;
use Magento\Framework\App\DefaultPathInterface;
use Magento\Framework\View\Element\Template\Context;

/**
 * Partners insight link for the customer account menu.
 */
class Link extends \Magento\Customer\Block\Account\SortLink
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * Constructor.
     *
     * @param Context $context
     * @param DefaultPathInterface $defaultPath
     * @param array $data
     */
    public function __construct(
        Context $context,
        DefaultPathInterface $defaultPath,
        Config $config,
        array $data = []
    ) {
        parent::__construct($context, $defaultPath, $data);
        $this->config = $config;
    }

    public function _toHtml()
    {
        if ($this->config->isAllowed()) {
            $this->setLabel($this->config->getPiConfig('menu_link_text'));
            $result = parent::_toHtml();
            return str_replace('<a', '<a target="_blank"', $result);
        } else {
            return '';
        }
    }
}
