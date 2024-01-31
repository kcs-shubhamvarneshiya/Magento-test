<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Lyonscg_Wishlist
 *
 * @category  Lyons
 * @package   Lyonscg_Wishlist
 * @author    Tanya Mamchik <tanya.mamchik@capgemini.com>
 * @copyright Copyright (c) 2021 Lyons Consulting Group (www.lyonscg.com)
 */
namespace Lyonscg\Wishlist\Block\Share\Email;

use Magento\Catalog\Model\Product\Configuration\Item\ItemResolverInterface;

/**
 * Class Items
 * @package Lyonscg\Wishlist\Block\Share\Email
 */
class Items extends \Magento\Wishlist\Block\Share\Email\Items
{
    /**
     * @var ItemResolverInterface
     */
    protected $itemResolver;

    /**
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\App\Http\Context $httpContext,
        ItemResolverInterface $itemResolver,
        array $data = []
    ) {
        $this->itemResolver = $itemResolver;
        parent::__construct(
            $context,
            $httpContext,
            $data
        );
    }

    /**
     * @param \Magento\Wishlist\Model\Item $item
     * @return \Magento\Catalog\Api\Data\ProductInterface
     */
//    public function getProductForThumbnail(\Magento\Wishlist\Model\Item $item)
//    {
//        return $this->itemResolver->getFinalProduct($item);
//    }
}
