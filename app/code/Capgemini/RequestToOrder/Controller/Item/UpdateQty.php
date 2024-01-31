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
use Capgemini\RequestToOrder\Controller\Request;
use Capgemini\RequestToOrder\Model\PermissionManager;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\UrlInterface;
use Magento\Framework\Pricing\Helper\Data as PricingData;

class UpdateQty extends Request implements HttpPostActionInterface
{
    /**
     * @var RequestInterface
     */
    protected RequestInterface $request;

    /**
     * @var ItemRepositoryInterface
     */
    protected ItemRepositoryInterface $itemRequestRepository;

    /**
     * @var JsonFactory
     */
    protected JsonFactory $jsonResultFactory;
    /**
     * @var PricingData
     */
    protected PricingData $pricingData;

    /**
     * @param RequestInterface $request
     * @param ItemRepositoryInterface $itemRequestRepository
     * @param JsonFactory $jsonResultFactory
     * @param RedirectInterface $redirect
     * @param PermissionManager $permissionManager
     * @param RedirectFactory $resultRedirectFactory
     * @param UrlInterface $urlInterface
     * @param Session $customerSession
     * @param PricingData $pricingData
     */
    public function __construct(
        RequestInterface        $request,
        ItemRepositoryInterface $itemRequestRepository,
        JsonFactory             $jsonResultFactory,
        RedirectInterface       $redirect,
        PermissionManager       $permissionManager,
        RedirectFactory         $resultRedirectFactory,
        UrlInterface            $urlInterface,
        Session                 $customerSession,
        PricingData $pricingData
    ) {
        parent::__construct(
            $redirect,
            $permissionManager,
            $resultRedirectFactory,
            $urlInterface,
            $customerSession
        );
        $this->pricingData = $pricingData;
        $this->request = $request;
        $this->itemRequestRepository = $itemRequestRepository;
        $this->jsonResultFactory = $jsonResultFactory;
    }

    /**
     * @return ResponseInterface|Json|ResultInterface
     */
    public function execute()
    {
        $result = $this->jsonResultFactory->create();
        try {
            $data = $this->request->getPostValue();
            $requestItemId = (int)$data['item_id'] ?? null;
            $newQty = (int)$data['qty'] ?? null;

            if (!$requestItemId) {
                throw new LocalizedException(__('Please, specify item id to update'));
            }

            if ($newQty === null) {
                throw new LocalizedException(__('Please, specify item qty'));
            }

            $requestItem = $this->itemRequestRepository->getById($requestItemId);
            $requestItem->setQty((int)$newQty);
            $this->itemRequestRepository->save($requestItem);
            $updatedData = $this->itemRequestRepository->getById($requestItemId);
            $result->setData(['price'=> $this->pricingData->currency($updatedData->getPrice()*$updatedData->getQty(), true, false)]);

        } catch (LocalizedException $exception) {
            $result->setData(['message' => $exception->getMessage()]);
            $result->setHttpResponseCode(500);
        }

        return $result;
    }
}
