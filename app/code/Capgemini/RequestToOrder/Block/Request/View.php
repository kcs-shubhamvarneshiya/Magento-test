<?php
/**
 * Capgemini_RequestToOrder
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\RequestToOrder\Block\Request;

use Capgemini\RequestToOrder\Api\Data\OrderRequestInterface;
use Capgemini\RequestToOrder\Api\OrderRequestRepositoryInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Phrase;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Capgemini\RequestToOrder\Helper\Data;
use Magento\Store\Model\ScopeInterface;

class View extends Template
{
    public const URI_REQUEST_SUBMIT = 'orequest/request/submit';

    /**
     * @var string
     */
    protected $_template = 'Capgemini_RequestToOrder::request/info.phtml';

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var OrderRequestInterface|RequestInterface|null
     */
    protected $orderRequest;

    /**
     * @var OrderRequestRepositoryInterface
     */
    protected $requestToOrderRepository;

    /**
     * @var UrlInterface
     */
    protected $urlInterface;

    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * @var RedirectFactory
     */
    protected $resultRedirectFactory;

    /**
     * @var Data
     */
    protected $helper;


    /**
     * @param Context $context
     * @param OrderRequestRepositoryInterface $requestToOrderRepository
     * @param CustomerSession $customerSession
     * @param RequestInterface $request
     * @param RedirectFactory $resultRedirectFactory
     * @param UrlInterface $urlInterface
     * @param Data $helper
     * @param array $data
     * @throws NoSuchEntityException
     */
    public function __construct(
        Context                         $context,
        OrderRequestRepositoryInterface $requestToOrderRepository,
        CustomerSession                 $customerSession,
        RequestInterface                $request,
        RedirectFactory                 $resultRedirectFactory,
        UrlInterface                    $urlInterface,
        Data                            $helper,
        array                           $data = []
    ) {
        parent::__construct($context, $data);
        $this->request = $request;
        $this->requestToOrderRepository = $requestToOrderRepository;
        $this->urlInterface = $urlInterface;
        $this->customerSession = $customerSession;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->helper = $helper;
        if ($this->getRequest()
            && $this->getRequest()->getStatus() == OrderRequestInterface::STATUS_ENABLE) {
            $this->setTemplate('Capgemini_RequestToOrder::request/form.phtml');
        }
    }

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        parent::_construct();
        $this->pageConfig->getTitle()->set(__('Request To Order'));
    }

    /**
     * @return OrderRequestInterface|RequestInterface|null
     * @throws NoSuchEntityException
     */
    public function getRequest()
    {
        $id = $this->request->getParam('id');

        if ($id) {
            $this->orderRequest = $this->requestToOrderRepository->getById((int)$id);
        }

        return $this->orderRequest;
    }

    /**
     * @return string
     */
    public function getSubmitUrl()
    {
        return $this->urlInterface->getUrl(self::URI_REQUEST_SUBMIT);
    }

    /**
     * @return Phrase|string
     * @throws NoSuchEntityException
     */
    public function toHtml()
    {
        if ($this->orderRequest !== null && $this->orderRequest->getCustomerId()
            != $this->customerSession->getCustomerId() || !$this->getRequest()) {
            return __('There are no requests');
        }

        return parent::toHtml();
    }

    /**
     * @return string|null
     * @throws LocalizedException
     */
    public function getCustomerServiceNumber(): string
    {
        return $this->helper->getCustomerServiceNumber((int)$this->_storeManager->getWebsite()->getId());
    }

    /**
     * @return string|null
     */
    public function getContactInformationCopy()
    {
        return $this->_scopeConfig->getValue('rto/general/contact_information_copy', ScopeInterface::SCOPE_STORE);
    }
}
