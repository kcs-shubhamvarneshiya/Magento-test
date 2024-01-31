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

namespace Capgemini\WishListViewList\Block;

use Magento\Framework\Phrase;
use Magento\Wishlist\Block\Link;

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
class ListLink extends Link
{
    /**
     * Short description
     *
     * @return string
     */
    public function getHref(): string
    {
        return $this->getUrl('wishlist/index/list');
    }

    /**
     * Short description
     *
     * @return Phrase
     */
    public function getLabel(): Phrase
    {
        return __('Projects');
    }
}
