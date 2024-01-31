<?php

namespace Capgemini\Company\Controller\Account;

use Capgemini\Company\Api\CompanyDocumentRepositoryInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Company\Api\CompanyManagementInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Exception\NoSuchEntityException;

class RemoveDocument extends \Magento\Framework\App\Action\Action implements HttpGetActionInterface
{
    /**
     * @var CompanyDocumentRepositoryInterface
     */
    protected $documentRepository;

    /**
     * @var CompanyManagementInterface
     */
    protected $companyManagement;

    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * GetDocument constructor.
     * @param Context $context
     * @param CompanyDocumentRepositoryInterface $documentRepository
     * @param CompanyManagementInterface $companyManagement
     * @param CustomerSession $customerSession
     */
    public function __construct(
        Context $context,
        CompanyDocumentRepositoryInterface $documentRepository,
        CompanyManagementInterface $companyManagement,
        CustomerSession $customerSession
    ) {
        parent::__construct($context);
        $this->documentRepository = $documentRepository;
        $this->companyManagement = $companyManagement;
        $this->customerSession = $customerSession;
    }
    public function execute()
    {
        if (!$this->customerSession->isLoggedIn()) {
            return $this->_error();
        }
        $customerId = $this->customerSession->getCustomerId();
        $company = $this->companyManagement->getByCustomerId($customerId);
        if ($company === null || !$company->getId()) {
            return $this->_error();
        }
        $documentId = $this->getRequest()->getParam('document_id');
        try {
            $document = $this->documentRepository->getById($documentId);
            $this->documentRepository->loadContents($document);
        } catch (NoSuchEntityException $e) {
            return $this->_error();
        }

        try {
            $document = $this->documentRepository->getById($documentId);
            $this->documentRepository->delete($document);
        } catch (NoSuchEntityException $e) {
            // no problem
        } catch (\Exception $e) {
            return $this->_error();
        }
        return $this->resultRedirectFactory->create()->setUrl($this->_redirect->getRefererUrl());
    }

    protected function _error()
    {
        return $this->resultFactory
            ->create('raw')
            ->setContents('')
            ->setHttpResponseCode(404);
    }
}
