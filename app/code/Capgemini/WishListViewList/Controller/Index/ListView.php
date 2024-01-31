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

use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\SessionException;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

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
class ListView implements HttpGetActionInterface
{
    /**
     * Short description
     *
     * @var PageFactory
     */
    protected PageFactory $resultPageFactory;

    /**
     * Short description
     *
     * @var RedirectInterface
     */
    protected RedirectInterface $redirect;

    /**
     * Short description
     *
     * @var FormKey
     */
    protected FormKey $formKey;

    /**
     * Short description
     *
     * @var Http
     */
    protected Http $request;

    /**
     * Short description
     *
     * @var Session
     */
    private Session $customerSession;

    /**
     * Short description
     *
     * @param PageFactory $resultPageFactory Param comment
     *                                             Short description
     * @param RedirectInterface $redirect Param comment
     *                                             Short
     *                                             description
     * @param Http $request Param comment
     *                                             Short
     *                                             description
     * @param FormKey $formKey Param comment
     *                                             Short
     *                                             description
     * @throws LocalizedException
     */
    public function __construct(
        PageFactory       $resultPageFactory,
        RedirectInterface $redirect,
        Http              $request,
        FormKey           $formKey,
        Session           $customerSession
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->redirect = $redirect;
        $this->formKey = $formKey;
        $this->request = $request;
        $this->customerSession = $customerSession;
        $this->request->setParam('form_key', $this->formKey->getFormKey());
    }

    /**
     * Short description
     *
     * @return Page
     *
     * @throws SessionException
     */
    public function execute(): Page
    {
        if (!$this->customerSession->isLoggedIn()) {
            $this->customerSession->authenticate();
        }
        /**
         * Short description
         *
         * @var Page $resultPage
         */
        $resultPage = $this->resultPageFactory->create();
        return $resultPage;
    }
}
