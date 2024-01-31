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

namespace Capgemini\WishListViewList\Block\Customer;

use Magento\Framework\Phrase;
use Magento\MultipleWishlist\Block\Customer\Sidebar;

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
class ListSidebar extends Sidebar
{
    /**
     * Retrieve block title
     *
     * @return Phrase
     */
    public function getTitle(): Phrase
    {
        if ($this->_getHelper()->isMultipleEnabled()) {
            return __('Projects');
        } else {
            return parent::getTitle();
        }
    }
}
