<?php
/**
 * Capgemini_RequestToOrder
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\RequestToOrder\Service;

use Magento\Customer\Model\Session as CustomerSession;

class GetCurrentRequest
{
    /**
     * @var CustomerSession
     */
    private $customerSession;

    /**
     * @param CustomerSession $customerSession
     */
    public function __construct(
        CustomerSession $customerSession
    )
    {
        $this->customerSession = $customerSession;
    }

    /**
     * @param null|int $requestId
     * @return void
     */
    public function setCurrentRequestId(?int $requestId)
    {
        $this->customerSession->setCurrentOrderRequestId($requestId);
    }

    /**
     * @return int
     */
    public function getCurrentRequestId()
    {
        return $this->customerSession->getCurrentOrderRequestId();
    }
}
