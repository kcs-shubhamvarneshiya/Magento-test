<?php
/**
 * Capgemini_CategoryAds
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\CategoryAds\Controller\Adminhtml;

use Capgemini\CategoryAds\Api\CategoryAdsRepositoryInterface;
use Capgemini\CategoryAds\Api\Data\CategoryAdsInterface;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Framework\App\RequestInterface;

abstract class Ads extends Action
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @var CategoryAdsRepositoryInterface
     */
    protected $adsRepository;

    /**
     * @var \Magento\Backend\Model\Session
     */
    protected $session;

    /**
     * @var ForwardFactory
     */
    protected $resultForwardFactory;

    /**
     * @var ResultFactory
     */
    protected $resultFactory;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var PageFactory
     */
    protected $pageFactory;

    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * @var RedirectFactory
     */
    protected $resultRedirectFactory;

    /**
     * @param Context $context
     * @param PageFactory $pageFactory
     * @param CategoryAdsRepositoryInterface $adsRepository
     * @param ForwardFactory $resultForwardFactory
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        CategoryAdsRepositoryInterface $adsRepository,
        ForwardFactory $resultForwardFactory,
        ManagerInterface $messageManager
    ) {
        parent::__construct($context);
        $this->context = $context;
        $this->pageFactory = $pageFactory;
        $this->adsRepository = $adsRepository;
        $this->messageManager = $messageManager;
        $this->session = $context->getSession();
        $this->resultForwardFactory = $resultForwardFactory;
        $this->resultFactory = $context->getResultFactory();
        $this->request = $context->getRequest();
        $this->resultRedirectFactory = $context->getResultRedirectFactory();
    }

    /**
     * @param $resultPage
     * @return Page
     */
    protected function initPage($resultPage): Page
    {
        $resultPage->setActiveMenu('Capgemini_CategoryAds::category_ads');

        $resultPage->addBreadcrumb(__('Manage Category Ads'), __('Manage Category Ads'));

        $resultPage->getConfig()->getTitle()->prepend(__('Manage Category Ads '));

        return $resultPage;
    }

    /**
     * @return CategoryAdsInterface
     * @throws NoSuchEntityException
     */
    protected function initModel(): CategoryAdsInterface
    {
        $model = $this->adsRepository->create();

        if ($this->request->getParam(CategoryAdsInterface::ID)) {
            $model = $this->adsRepository->getById($this->request->getParam(CategoryAdsInterface::ID));
        }

        return $model;
    }

    /**
     * Check for is allowed
     *
     * @return boolean
     */
    protected function _isAllowed(): bool
    {
        return $this->context->getAuthorization()->isAllowed('Capgemini_CategoryAds::category_ads');
    }
}
