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

namespace Capgemini\WishListViewList\Controller\Index;

use Exception;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;
use Magento\MultipleWishlist\Helper\Data as WishListHelper;
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
class Massdelete implements HttpGetActionInterface, HttpPostActionInterface
{
    /**
     * Short description
     *
     * @var RequestInterface
     */
    protected RequestInterface $request;

    /**
     * Short description
     *
     * @var WishlistFactory
     */
    protected WishlistFactory $wishlistFactory;

    /**
     * Short description
     *
     * @var ResultFactory
     */
    protected ResultFactory $resultFactory;

    /**
     * Short description
     *
     * @var MessageManagerInterface
     */
    protected MessageManagerInterface $messageManager;

    /**
     * Short description
     *
     * @var WishListHelper
     */
    protected WishListHelper $wishlistHelper;

    /**
     * Short description
     *
     * @param RequestInterface        $request         Param comment
     *                                                 Short
     *                                                 description
     * @param WishlistFactory         $wishlistFactory Param comment
     *                                                 Short description
     * @param ResultFactory           $resultFactory   Param comment
     *                                                 Short
     *                                                 description
     * @param MessageManagerInterface $messageManager  Param comment
     *                                                 Short
     *                                                 description
     * @param WishListHelper          $wishlistHelper  Param comment
     */
    public function __construct(
        RequestInterface        $request,
        WishlistFactory         $wishlistFactory,
        ResultFactory           $resultFactory,
        MessageManagerInterface $messageManager,
        WishListHelper          $wishlistHelper
    ) {
        $this->request = $request;
        $this->wishlistFactory = $wishlistFactory;
        $this->resultFactory = $resultFactory;
        $this->messageManager = $messageManager;
        $this->wishlistHelper = $wishlistHelper;
    }

    /**
     * Short description
     *
     * @return ResponseInterface|Redirect&ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        try {
            $params = $this->request->getParams();
            if (isset($params['wishlist_id'])) {
                $selected = [$params['wishlist_id']];
            } else {
                $selected = $params['selected'];
            }
            foreach ($selected as $item) {
                $wishlist = $this->wishlistFactory->create()->load($item);
                if ($this->wishlistHelper->isWishlistDefault($wishlist)) {
                    throw new LocalizedException(
                        __("The default project can't be deleted.")
                    );
                }
                $wishlist->delete();
                $this->wishlistHelper->calculate();
            }
            $this->messageManager->addSuccessMessage(
                __('Selected projects has been deleted.')
            );
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (Exception $e) {
            $message = __('We can\'t delete the project right now.');
            $this->messageManager->addErrorMessage($message);
        }
        return $resultRedirect->setPath('wishlist/index/list');
    }
}
