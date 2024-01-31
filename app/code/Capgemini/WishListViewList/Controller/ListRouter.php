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

namespace Capgemini\WishListViewList\Controller;

use Magento\Framework\App\Action\Forward;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\RouterInterface;

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
class ListRouter implements RouterInterface
{
    public const MODULE_NAME = 'cpwishlist';
    public const CONTROLLER_NAME = 'index';
    public const ACTION_NAME = 'listview';

    /**
     * Short description
     *
     * @var ActionFactory
     */
    protected ActionFactory $actionFactory;

    /**
     * Short description
     *
     * @param ActionFactory $actionFactory Param comment
     */
    public function __construct(ActionFactory $actionFactory)
    {
        $this->actionFactory = $actionFactory;
    }

    /**
     * Short description
     *
     * @param RequestInterface $request Short description
     *
     * @return bool|ActionInterface
     */
    public function match(RequestInterface $request)
    {
        $identifier = trim($request->getPathInfo(), '/');
        $urlParams = explode('/', $identifier);

        if (in_array('wishlist', $urlParams)
            && in_array('list', $urlParams)
        ) {
            $request->setModuleName(self::MODULE_NAME)
                ->setControllerName(self::CONTROLLER_NAME)
                ->setActionName(self::ACTION_NAME)
                ->setParams($request->getParams());

            return $this->actionFactory->create(Forward::class);
        } elseif ($request->getModuleName() === self::MODULE_NAME) {
            return false;
        }

        return false;
    }
}
