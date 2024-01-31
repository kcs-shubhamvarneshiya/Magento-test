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

namespace Capgemini\WishListViewList\Ui\Component\Listing\Column;

use Exception;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
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
class ItemsCount extends Column
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
     * @param ContextInterface   $context            Param comment
     *                                               Short
     *                                               description
     * @param UiComponentFactory $uiComponentFactory Param comment
     *                                               Short
     *                                               description
     * @param WishlistFactory    $wishlistFactory    Param comment
     *                                               Short
     *                                               description
     * @param array              $components         Param comment
     *                                               Short
     *                                               description
     * @param array              $data               Param comment
     */
    public function __construct(
        ContextInterface   $context,
        UiComponentFactory $uiComponentFactory,
        WishlistFactory    $wishlistFactory,
        array              $components = [],
        array              $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->wishlistFactory = $wishlistFactory;
    }

    /**
     *  Short description
     *
     * @param array $dataSource Param comment
     *
     * @return array
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                try {
                    $wishlist = $this->wishlistFactory->create();
                    $wishlist->load($item['wishlist_id']);
                    $itemsCount = $wishlist->getItemsCount();
                } catch (Exception $e) {
                    $itemsCount = 0;
                }
                $item['items'] = $itemsCount;
            }
        }

        return $dataSource;
    }
}
