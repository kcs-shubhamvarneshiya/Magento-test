<?php
/**
 * Capgemini_RequestToOrder
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\RequestToOrder\Controller\Item;

use Capgemini\RequestToOrder\Api\OrderRequest\ItemRepositoryInterface;
use Capgemini\RequestToOrder\Api\OrderRequestRepositoryInterface;
use Capgemini\RequestToOrder\Controller\Request;
use Capgemini\RequestToOrder\Helper\Data;
use Capgemini\RequestToOrder\Model\PermissionManager;
use Capgemini\RequestToOrder\Model\RequestManagement;
use Magento\Catalog\Model\ProductRepository;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\StoreManagerInterface;

class Add extends Request implements HttpPostActionInterface
{
    /**
     * @var RequestInterface
     */
    protected RequestInterface $request;

    /**
     * @var RequestManagement
     */
    protected RequestManagement $orderRequestManagement;

    /**
     * @var ProductRepository
     */
    protected ProductRepository $productRepository;

    /**
     * @var ItemRepositoryInterface
     */
    protected ItemRepositoryInterface $itemRequestRepository;

    /**
     * @var OrderRequestRepositoryInterface
     */
    protected OrderRequestRepositoryInterface $requestToOrderRepository;

    /**
     * @var JsonFactory
     */
    protected JsonFactory $jsonResultFactory;

    /**
     * @var Data
     */
    protected Data $helper;

    /**
     * @var StoreManagerInterface
     */
    protected StoreManagerInterface $storeManager;

    /**
     * @param RequestInterface $request
     * @param Data $helper
     * @param Session $customerSession
     * @param RequestManagement $orderRequestManagement
     * @param ItemRepositoryInterface $itemRequestRepository
     * @param ProductRepository $productRepository
     * @param OrderRequestRepositoryInterface $requestToOrderRepository
     * @param JsonFactory $jsonResultFactory
     * @param StoreManagerInterface $storeManager
     * @param RedirectInterface $redirect
     * @param PermissionManager $permissionManager
     * @param RedirectFactory $resultRedirectFactory
     * @param UrlInterface $urlInterface
     */
    public function __construct(
        RequestInterface                $request,
        Data                            $helper,
        Session                         $customerSession,
        RequestManagement               $orderRequestManagement,
        ItemRepositoryInterface         $itemRequestRepository,
        ProductRepository               $productRepository,
        OrderRequestRepositoryInterface $requestToOrderRepository,
        JsonFactory                     $jsonResultFactory,
        StoreManagerInterface           $storeManager,
        RedirectInterface               $redirect,
        PermissionManager               $permissionManager,
        RedirectFactory                 $resultRedirectFactory,
        UrlInterface                    $urlInterface
    ) {
        parent::__construct(
            $redirect,
            $permissionManager,
            $resultRedirectFactory,
            $urlInterface,
            $customerSession
        );
        $this->request = $request;
        $this->helper = $helper;
        $this->orderRequestManagement = $orderRequestManagement;
        $this->productRepository = $productRepository;
        $this->itemRequestRepository = $itemRequestRepository;
        $this->requestToOrderRepository = $requestToOrderRepository;
        $this->jsonResultFactory = $jsonResultFactory;
        $this->storeManager = $storeManager;
    }

    /**
     * @return ResponseInterface|Json|Redirect|ResultInterface
     */
    public function execute()
    {
        $result = $this->jsonResultFactory->create();
        try {
            if (!$this->validateCustomer()) {
                throw new LocalizedException(__('Please, login to create order request'));
            }

            $data = $this->request->getPostValue();


            if (isset($data['product_id']) &&
                isset($data['qty'])) {
                $orderRequest = $this->orderRequestManagement->getCustomerRequest($this->getCustomer()->getId());
                $productId = $data['product_id'];
                $optionId = $data['option_id'] ?? null;

                $loadedProduct = $mainProduct = $this->productRepository->getById($productId);

                if ($optionId) {
                    $loadedProduct = $this->productRepository->getById($optionId);
                }

                $requestItem = $this->itemRequestRepository->create();
                $requestItem->setRequest($orderRequest);
                $requestItem->setSku($loadedProduct->getSku());
                $requestItem->setQty((int)$data['qty']);
                $requestItem->setProductId((int)$loadedProduct->getId());
                $requestItem->setPrice((float) ($data['price'] ?? $loadedProduct->getPrice()));
                $requestItem->setName($loadedProduct->getName());

                $orderRequest->addItem($requestItem);
                $this->requestToOrderRepository->save($orderRequest);

                $addedItemData = $this->prepareItemResponse($loadedProduct, $data['qty']);
                $addedItemData['image'] = $requestItem->getImageUrl();
                $addedItemData['requestId'] = $orderRequest->getRtoId();

                $result->setData(
                    [
                        'success' => true,
                        'item' => $addedItemData
                    ]
                );
            }
        } catch (LocalizedException $exception) {
            $result->setData(['message' => $exception->getMessage()]);
            $result->setHttpResponseCode(500);
        }

        return $result;
    }

    /**
     * @param $product
     * @param string $qty
     * @return array
     * @throws LocalizedException
     */
    private function prepareItemResponse($product, string $qty)
    {
        $finishdescriptionAttribute = $product->getResource()->getAttribute('finishdescription');
        $finishDescriptionText = $finishdescriptionAttribute->getSource()->getOptionText($product->getFinishdescription());
        return [
            'name' => $product->getName(),
            'price' => number_format((float)$product->getPrice(), 2),
            'sku' => $product->getSku(),
            'qty' => $qty,
            'availability' => $product->getAvailabilityMessage(),
            'finish' => $finishDescriptionText,
            'text' => $this->helper->getPdpModalText((int)$this->storeManager->getWebsite()->getId())
        ];
    }
}
