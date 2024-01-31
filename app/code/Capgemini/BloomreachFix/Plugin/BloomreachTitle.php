<?php

namespace Capgemini\BloomreachFix\Plugin;

use Bloomreach\Connector\ViewModel\Head\ScriptInit;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\BlockInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Page\Config;

class BloomreachTitle
{
    /**
     * @var Config
     */
    private $pageConfig;

    public function __construct(Config $pageConfig)
    {
        $this->pageConfig = $pageConfig;
    }

    /**
     * @param ScriptInit $subject
     * @param callable $proceed
     * @param $block
     * @return string|void
     */
    public function aroundGetCurrentPageTitle(ScriptInit $subject, callable $proceed, $block)
    {
        if (!$block instanceof BlockInterface) {

            return $this->pageConfig->getTitle()->get();
        }

        try {
            /** @var Template $block */
            if(!$block->getLayout()->getBlock('page.main.title')) {

                return $this->pageConfig->getTitle()->get();
            }

        } catch (LocalizedException  $exception) {

            return $this->pageConfig->getTitle()->get();
        }

        return $proceed($block);
    }
}
