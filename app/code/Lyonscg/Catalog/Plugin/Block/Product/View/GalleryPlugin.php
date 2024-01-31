<?php
/**
 * Lyonscg_Catalog
 *
 * @category  Lyons
 * @package   Lyonscg_Catalog
 * @author    Logan Montgomery <logan.montgomery@capgemini.com>
 * @author    Tanya Mamchik<tanya.mamchik@capgemini.com>
 * @copyright Copyright (c) 2020 Lyons Consulting Group (www.lyonscg.com)
 */

namespace Lyonscg\Catalog\Plugin\Block\Product\View;

/**
 * Class GalleryPlugin
 * @package Lyonscg\Catalog\Plugin\Block\Product\View
 */
class GalleryPlugin
{
    /**
     * @var \Lyonscg\Catalog\Helper\Data
     */
    protected $helper;

    /**
     * GalleryPlugin constructor.
     * @param \Lyonscg\Catalog\Helper\Data $helper
     */
    public function __construct(
        \Lyonscg\Catalog\Helper\Data $helper
    ) {
        $this->helper = $helper;
    }

    /**
     * @param \Magento\Catalog\Block\Product\View\Gallery $subject
     * @param $product
     * @return \Lyonscg\Catalog\Helper\DataObject|\Magento\Catalog\Api\Data\ProductInterface|\Magento\Catalog\Model\Product|mixed
     */
    public function afterGetProduct(\Magento\Catalog\Block\Product\View\Gallery $subject, $product)
    {
        return ($subject instanceof \Magento\ProductVideo\Block\Product\View\Gallery) ?
            $product : $this->helper->getChildProduct($product);
    }
}
