<?php
/**
 * Capgemini_AmastyStoreLocator
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\RequestToOrder\Controller\Cart;

use Capgemini\RequestToOrder\Api\OrderRequest\ItemRepositoryInterface;
use Capgemini\RequestToOrder\Api\OrderRequestRepositoryInterface;
use Capgemini\RequestToOrder\Model\RequestManagement;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Psr\Log\LoggerInterface;

/**
 * Move cart item to the request order
 */
class Move implements HttpPostActionInterface
{
    protected RequestInterface $request;
    protected Session $checkoutSession;
    protected MessageManagerInterface $messageManager;
    protected LoggerInterface $logger;
    protected RedirectFactory $redirectFactory;
    protected RequestManagement $orderRequestManagement;
    protected ItemRepositoryInterface $itemRequestRepository;
    protected OrderRequestRepositoryInterface $requestToOrderRepository;
    protected CartRepositoryInterface $quoteRepository;

    /**
     * @param RequestInterface $request
     * @param Session $checkoutSession
     * @param MessageManagerInterface $messageManager
     * @param LoggerInterface $logger
     * @param RedirectFactory $redirectFactory
     * @param RequestManagement $orderRequestManagement
     * @param ItemRepositoryInterface $itemRequestRepository
     * @param OrderRequestRepositoryInterface $requestToOrderRepository
     * @param CartRepositoryInterface $quoteRepository
     */
    public function __construct(
        RequestInterface $request,
        Session $checkoutSession,
        MessageManagerInterface $messageManager,
        LoggerInterface $logger,
        RedirectFactory $redirectFactory,
        RequestManagement $orderRequestManagement,
        ItemRepositoryInterface $itemRequestRepository,
        OrderRequestRepositoryInterface $requestToOrderRepository,
        CartRepositoryInterface $quoteRepository
    ) {
        $this->request = $request;
        $this->checkoutSession = $checkoutSession;
        $this->messageManager = $messageManager;
        $this->logger = $logger;
        $this->redirectFactory = $redirectFactory;
        $this->orderRequestManagement = $orderRequestManagement;
        $this->itemRequestRepository = $itemRequestRepository;
        $this->requestToOrderRepository = $requestToOrderRepository;
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function execute()
    {
        $id = (int)$this->request->getParam('id');
        if ($id) {
            try {
                $quote = $this->checkoutSession->getQuote();
                $item = $quote->getItemById($id);
                if ($item) {
                    if ($item->getProductType() == 'configurable') {
                        $children = $item->getChildren();
                        if ($children) {
                            $childItem = array_shift($children);
                        }
                    }
                    $orderRequest = $this->orderRequestManagement->getCustomerRequest($quote->getCustomerId());
                    $requestItem = $this->itemRequestRepository->create();
                    $requestItem->setRequest($orderRequest);
                    $requestItem->setSku(isset($childItem) ? $childItem->getProduct()->getSku() : $item->getProduct()->getSku());
                    $requestItem->setQty($item->getQty());
                    $requestItem->setProductId(isset($childItem) ? $childItem->getProduct()->getId() : $item->getProduct()->getId());
                    $requestItem->setPrice($item->getPrice());
                    $requestItem->setName(isset($childItem) ? $childItem->getProduct()->getName() : $item->getProduct()->getName());
                    $orderRequest->addItem($requestItem);
                    $this->requestToOrderRepository->save($orderRequest);

                    $quote->removeItem($id);
                    $quote->setTotalsCollectedFlag(false);
                    $this->quoteRepository->save($quote);
                }
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('We can\'t move the item.'));
                $this->logger->critical($e);
            }
        }
        $redirect = $this->redirectFactory->create();
        $redirect->setPath('checkout/cart');
        return $redirect;
    }
}
