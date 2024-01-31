<?php

namespace Capgemini\BloomreachThematicSitemap\Block\System\Config\Form;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Exception\LocalizedException;

class DownloadButton extends Field
{
    protected $_template = 'Capgemini_BloomreachThematicSitemap::system/config/button/button.phtml';


    public function render(AbstractElement $element)
    {
        $element->unsScope();

        return parent::render($element);
    }

    public function getAjaxUrl(): string
    {
        return $this->getUrl('bloomreach_thematic_sitemap/ajax/download');
    }

    /**
     * @throws LocalizedException
     */
    public function getButtonHtml()
    {
        $button = $this->getLayout()->createBlock(
            'Magento\Backend\Block\Widget\Button'
        )->setData(
            [
                'id' => 'thematic-sitemaps-download-btn',
                'label' => __('Download Sitemaps'),
            ]
        );

        return $button->toHtml();
    }

    protected function _getElementHtml(AbstractElement $element)
    {
        return $this->_toHtml();
    }
}
