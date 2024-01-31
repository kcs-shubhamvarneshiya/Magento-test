<?php
/**
 * Capgemini_RequestToOrder
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\RequestToOrder\Block\Request\View;

use Capgemini\RequestToOrder\Api\OrderRequestRepositoryInterface;
use Capgemini\RequestToOrder\Block\Request\View;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template\Context;
use Capgemini\RequestToOrder\Helper\Data;

class Success extends View
{
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
    )
    {
        parent::__construct(
            $context,
            $requestToOrderRepository,
            $customerSession,
            $request,
            $resultRedirectFactory,
            $urlInterface,
            $helper,
            $data
        );
        $this->setTemplate('Capgemini_RequestToOrder::request/success.phtml');
    }
}
