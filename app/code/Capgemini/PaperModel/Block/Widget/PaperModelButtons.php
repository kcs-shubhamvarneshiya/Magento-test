<?php
declare(strict_types=1);

namespace Capgemini\PaperModel\Block\Widget;

use Capgemini\PaperModel\ViewModel\PaperModelProduct;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;

class PaperModelButtons extends Template implements BlockInterface
{
    protected $_template = "Capgemini_PaperModel::widget/paper_model_buttons.phtml";

    /**
     * @return bool
     */
    public function isPaperModelSalable()
    {
        try {
            $paperModelBlock = $this->getLayout()->getBlock('pdp-additional-paper-model');
            /** @var PaperModelProduct $paperModelProductViewModel */
            $paperModelProductViewModel = $paperModelBlock->getData('paper_model_product_view_model');

            return $paperModelProductViewModel && $paperModelProductViewModel->isSalable();
        } catch (LocalizedException $exception) {

            return false;
        }
    }
}
