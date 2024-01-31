<?php

namespace Capgemini\PaperModel\Block;

use Lyonscg\ConfigurableSimple\Block\Specifications;
use Magento\Catalog\Api\Data\ProductInterface;

class PaperModel extends Specifications
{
    const PAPER_MODEL_PDF_ATTRIBUTE = 'paper_model_pdf';

    /**
     * @param ProductInterface $product
     * @return string
     */
    public function getPaperModelPreviewLink(ProductInterface $product): string
    {
        try {
            $fileName = $this->helper->productAttribute(
                $product,
                $product->getData(self::PAPER_MODEL_PDF_ATTRIBUTE),
                self::PAPER_MODEL_PDF_ATTRIBUTE
            );
            if ($this->productFileExists($fileName)) {
                return $this->getMediaUrl() . self::DOC_FOLDER . '/' . $fileName;
            }
        } catch (\Exception $e) {
        }
        return "#";
    }
}
