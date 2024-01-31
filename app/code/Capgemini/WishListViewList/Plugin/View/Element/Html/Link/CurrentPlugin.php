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

namespace Capgemini\WishListViewList\Plugin\View\Element\Html\Link;

use Capgemini\WishListViewList\Controller\ListRouter;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\Html\Link\Current;

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
class CurrentPlugin
{
    /**
     * Request
     *
     * @var RequestInterface
     */
    protected RequestInterface $request;

    /**
     * Short description
     *
     * @param RequestInterface $request Param comment
     */
    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * Short description
     *
     * @param Current $subject Param comment
     * @param bool    $result  Param comment
     *
     * @return bool
     */
    public function afterIsCurrent(
        Current $subject,
        bool $result
    ): bool {
        if ($subject->getPath() == 'wishlist/index/list'
            && $this->_isMyProjectsPage()
        ) {
            return true;
        }
        return $result;
    }

    /**
     *  Short description
     *
     * @return bool
     */
    private function _isMyProjectsPage(): bool
    {
        $controllerName = (string)$this->request->getControllerName();
        if ($this->request->getModuleName()== ListRouter::MODULE_NAME
            && $controllerName
            && $this->request->getActionName()== ListRouter::ACTION_NAME
        ) {
            return true;
        }

        return false;
    }
}
