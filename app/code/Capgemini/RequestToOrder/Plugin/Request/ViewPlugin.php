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
use Capgemini\RequestToOrder\Block\Request\View;
use Capgemini\RequestToOrder\Service\GetCurrentRequest as RequestService;
use Magento\Framework\App\RequestInterface;

class ViewPlugin
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var RequestService
     */
    protected $getCurrentRequestService;

    /**
     * @param RequestService $getCurrentRequestService
     * @param RequestInterface $request
     */
    public function __construct(
        RequestService   $getCurrentRequestService,
        RequestInterface $request
    )
    {
        $this->getCurrentRequestService = $getCurrentRequestService;
        $this->request = $request;
    }

    /**
     * @param View $subject
     * @param OrderRequestInterface|RequestInterface|null $result
     * @return OrderRequestInterface|RequestInterface|null
     */
    public function afterGetRequest(
        View $subject,
             $result
    )
    {
        if ($result && $result->getId()) {
            $this->getCurrentRequestService->setCurrentRequestId((int)$result->getId());
        }
        return $result;
    }
}
