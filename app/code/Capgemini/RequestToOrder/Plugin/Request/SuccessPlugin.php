<?php
/**
 * Capgemini_RequestToOrder
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\RequestToOrder\Plugin\Request;

use Capgemini\RequestToOrder\Api\Data\OrderRequestInterface;
use Capgemini\RequestToOrder\Api\OrderRequestRepositoryInterface;
use Capgemini\RequestToOrder\Block\Request\View\Success;
use Capgemini\RequestToOrder\Service\GetCurrentRequest as RequestService;
use Magento\Framework\Exception\NoSuchEntityException;

class SuccessPlugin
{
    /**
     * @var RequestService
     */
    protected $getCurrentRequestService;

    /**
     * @var OrderRequestRepositoryInterface
     */
    protected $requestToOrderRepository;

    /**
     * @param RequestService $getCurrentRequestService
     * @param OrderRequestRepositoryInterface $requestToOrderRepository
     */
    public function __construct(
        RequestService                  $getCurrentRequestService,
        OrderRequestRepositoryInterface $requestToOrderRepository
    )
    {
        $this->getCurrentRequestService = $getCurrentRequestService;
        $this->requestToOrderRepository = $requestToOrderRepository;
    }

    /**
     * @param Success $subject
     * @param $result
     * @return OrderRequestInterface|mixed|null
     * @throws NoSuchEntityException
     */
    public function afterGetRequest(
        Success $subject,
                $result
    )
    {
        $currentRequestId = $this->getCurrentRequestService->getCurrentRequestId();
        if ($currentRequestId) {
            return $this->requestToOrderRepository->getById($currentRequestId);
        }
        return $result;
    }

    /**
     * @param Success $subject
     * @param $result
     * @return mixed
     */
    public function afterToHtml(
        Success $subject,
                $result
    )
    {
        $this->getCurrentRequestService->setCurrentRequestId(null);
        return $result;
    }
}
