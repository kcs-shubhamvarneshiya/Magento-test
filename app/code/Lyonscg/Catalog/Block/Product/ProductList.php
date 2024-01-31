<?php
/**
 * Lyonscg_Catalog
 *
 * @category  Lyons
 * @package   Lyonscg_Catalog
 * @author    Tanya Mamchik<tanya.mamchik@capgemini.com>
 * @copyright Copyright (c) 2020 Lyons Consulting Group (www.lyonscg.com)
 */

namespace Lyonscg\Catalog\Block\Product;


use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Checkout\Model\ResourceModel\Cart;
use Magento\Checkout\Model\Session;
use Magento\Framework\Module\Manager;
use Lyonscg\Catalog\Helper\Data as CatalogHelper;
use Lyonscg\Catalog\Helper\Config as ConfigHelper;

/**
 * Class ProductList
 * @package Lyonscg\Catalog\Block\Product
 */
class ProductList extends \Magento\Catalog\Block\Product\ProductList\Related
{
    /**
     * @var CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * @var CatalogHelper
     */
    protected $catalogHelper;

    /**
     * @var ConfigHelper
     */
    protected $configHelper;

    /**
     * @param Context $context
     * @param Cart $checkoutCart
     * @param Visibility $catalogProductVisibility
     * @param Session $checkoutSession
     * @param Manager $moduleManager
     * @param CollectionFactory $productCollectionFactory
     * @param CatalogHelper $catalogHelper
     * @param ConfigHelper $configHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        Cart $checkoutCart,
        Visibility $catalogProductVisibility,
        Session $checkoutSession,
        Manager $moduleManager,
        CollectionFactory $productCollectionFactory,
        CatalogHelper $catalogHelper,
        ConfigHelper $configHelper,
        array $data = []
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->catalogHelper = $catalogHelper;
        $this->configHelper = $configHelper;
        parent::__construct(
            $context,
            $checkoutCart,
            $catalogProductVisibility,
            $checkoutSession,
            $moduleManager
        );
    }
}
