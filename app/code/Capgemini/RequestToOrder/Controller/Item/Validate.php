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

use Capgemini\RequestToOrder\Model\PermissionManager;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory;

class Validate implements HttpPostActionInterface
{
    /**
     * @var JsonFactory
     */
    protected JsonFactory $jsonResultFactory;

    /**
     * @var PermissionManager
     */
    protected PermissionManager $permissionManager;

    /**
     * @var RequestInterface
     */
    protected RequestInterface $request;

    /**
     * @var CustomerSession
     */
    protected CustomerSession $customerSession;

    /**
     * @var ProductRepositoryInterface
     */
    protected ProductRepositoryInterface $productRepository;

    /**
     * @param JsonFactory $jsonResultFactory
     * @param PermissionManager $permissionManager
     * @param RequestInterface $request
     * @param ProductRepositoryInterface $productRepository
     * @param CustomerSession $customerSession
     */
    public function __construct(
        JsonFactory $jsonResultFactory,
        PermissionManager $permissionManager,
        RequestInterface $request,
        ProductRepositoryInterface $productRepository,
        CustomerSession $customerSession
    ) {
        $this->request = $request;
        $this->jsonResultFactory = $jsonResultFactory;
        $this->permissionManager = $permissionManager;
        $this->productRepository = $productRepository;
        $this->customerSession = $customerSession;
    }

    public function execute()
    {
        $result = $this->jsonResultFactory->create();

        $productId = $this->request->getParam('id');
        $product = $this->productRepository->getById($productId);

        $customer = $this->customerSession->getCustomer();

        $result->setData(
            [
                'success' => $this->permissionManager->isRequestToOrderAllowed($customer, $product)
            ]
        );

        return $result;
    }
}
