<?php
/**
 * Capgemini_ProductPdf
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\ProductPdf\Block;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class PrintButton extends Template
{
    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @param Context $context
     * @param Session $customerSession
     * @param CustomerRepositoryInterface $customerRepository
     * @param array $data
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        CustomerRepositoryInterface $customerRepository,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->customerSession = $customerSession;
        $this->customerRepository = $customerRepository;
    }

    /**
     * @return bool
     */
    public function isCompanyPrice(): bool
    {
        try {
            $customer = $this->customerSession->getCustomer();
            if ($customer->getId()) {
                $loadedCustomer = $this->customerRepository->getById($customer->getId());
                $companyAttr = $loadedCustomer->getExtensionAttributes()->getCompanyAttributes();
                return (bool)$companyAttr->getCompanyId();
            }
        } catch (\Exception $ex) {}
        return false;
    }
}
