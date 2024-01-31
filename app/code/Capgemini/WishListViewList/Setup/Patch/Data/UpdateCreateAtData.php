<?php
/**
 * Capgemini_WishListViewList
 * php version 7.4.27
 *
 * @category  Capgemini
 * @package   Capgemini_WishListViewList
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link      https://www.capgemini.com
 */
declare(strict_types=1);

namespace Capgemini\WishListViewList\Setup\Patch\Data;

use Exception;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Wishlist\Model\ResourceModel\Item\CollectionFactory;
use Magento\Wishlist\Model\WishlistFactory;

/**
 * Short description
 *
 * @category  Capgemini
 * @package   Capgemini_WishListViewList
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link      https://www.capgemini.com
 */
class UpdateCreateAtData implements DataPatchInterface
{
    /**
     * Short description
     *
     * @var WishlistFactory
     */
    protected WishlistFactory $wishlistFactory;

    /**
     * Short description
     *
     * @var CollectionFactory
     */
    protected CollectionFactory $itemsCollectionFactory;

    /**
     * Short description
     *
     * @param WishlistFactory   $wishlistFactory        Param comment
     *                                                  Short
     *                                                  description
     * @param CollectionFactory $itemsCollectionFactory Param comment
     */
    public function __construct(
        WishlistFactory   $wishlistFactory,
        CollectionFactory $itemsCollectionFactory
    ) {
        $this->wishlistFactory = $wishlistFactory;
        $this->itemsCollectionFactory = $itemsCollectionFactory;
    }

    /**
     * Short description
     *
     * @return array
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * Short description
     *
     * @return void
     * @throws Exception
     */
    public function apply()
    {
        $items = $this->itemsCollectionFactory->create();
        $wishlistData = [];
        foreach ($items as $item) {
            $addedAt = $item->getAddedAt();
            $wishlistId = $item->getWishlistId();
            $wishlistData[$wishlistId][] = $addedAt;
        }
        foreach ($wishlistData as $id => $data) {
            $wishlist = $this->wishlistFactory->create()->load($id);
            if (isset($data[0])) {
                $wishlist->setCreatedAt($data[0]);
            } else {
                $wishlist->setCreatedAt($wishlist->getUpdatedAt());
            }
            $wishlist->save();
        }
    }

    /**
     * Short description
     *
     * @return array
     */
    public function getAliases(): array
    {
        return [];
    }
}
