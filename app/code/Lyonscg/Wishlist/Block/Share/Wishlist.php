<?php
/**
 * Lyonscg_Wishlist
 *
 * @category  Lyons
 * @package   Lyonscg_Wishlist
 * @author    Tanya Mamchik <tanya.mamchik@capgemini.com>
 * @copyright Copyright (c) 2021 Lyons Consulting Group (www.lyonscg.com)
 */

namespace Lyonscg\Wishlist\Block\Share;

use Magento\Catalog\Model\Product\Configuration\Item\ItemResolverInterface;

/**
 * Class Wishlist
 * @package Lyonscg\Wishlist\Block\Share
 */
class Wishlist extends \Magento\Wishlist\Block\Share\Wishlist
{
    /**
     * @var ItemResolverInterface
     */
    protected $itemResolver;

    /**
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        ItemResolverInterface $itemResolver,
        array $data = []
    ) {
        $this->itemResolver = $itemResolver;
        parent::__construct(
            $context,
            $httpContext,
            $customerRepository,
            $data
        );
    }

    /**
     * @param \Magento\Wishlist\Model\Item $item
     * @return \Magento\Catalog\Api\Data\ProductInterface
     */
    public function getProductForThumbnail(\Magento\Wishlist\Model\Item $item)
    {
        return $this->itemResolver->getFinalProduct($item);
    }
}
