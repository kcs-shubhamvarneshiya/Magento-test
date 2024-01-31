<?php

namespace Capgemini\Company\Block\Company\Account;

use Capgemini\Company\Api\CompanyDocumentRepositoryInterface;
use Capgemini\Company\Api\Data\CompanyDocumentInterface;
use Capgemini\Company\Helper\Document as DocumentHelper;
use Magento\Company\Api\CompanyManagementInterface;
use Magento\Company\Api\Data\CompanyInterfaceFactory;
use Magento\Customer\Model\Session as CustomerSession;

class DocumentList extends \Magento\Framework\View\Element\Template
{
    protected $_template = 'Capgemini_Company::company/account/document-list.phtml';

    protected $_company = null;

    protected $documentRepository;

    protected $companyManagement;

    protected $companyFactory;

    protected $session;

    protected $documentHelper;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        CompanyDocumentRepositoryInterface $documentRepository,
        CompanyManagementInterface $companyManagement,
        CustomerSession $session,
        CompanyInterfaceFactory $companyFactory,
        DocumentHelper $documentHelper,
        $data = []
    ) {
        parent::__construct($context, $data);
        $this->documentRepository = $documentRepository;
        $this->companyManagement = $companyManagement;
        $this->session = $session;
        $this->companyFactory = $companyFactory;
        $this->documentHelper = $documentHelper;
    }

    public function getDocumentType()
    {
        return $this->_getData('document_type');
    }

    public function getJsLayout()
    {
        $documentsData = $this->getDocumentsData();
        if (empty($documentsData)) {
            return parent::getJsLayout();
        }
        try {
            if ($this->getDocumentType() == 'tax-exempt') {
                $which = 'exemptDocuments';
                $fieldset = 'exempt-documents-fieldset';
                $component = 'taxExempt';
            } else {
                $which = 'proofDocuments';
                $fieldset = 'proof-documents-fieldset';
                $component = 'proofOfTrade';
            }
            $layout = $this->jsLayout;
            $layout['components'][$which]['children'][$fieldset]['children'][$component]['value'] = $documentsData;
            return json_encode($layout);
        } catch (\Exception $e) {
            return parent::getJsLayout();
        }
    }

    public function getDocumentsData()
    {
        $data = [];
        $documents = $this->documentRepository->getForCompany($this->getCompany());
        /** @var \Capgemini\Company\Api\Data\CompanyDocumentInterface $document */
        foreach ($documents as $document) {
            if ($document->getDocumentType() !== $this->getDocumentType()) {
                continue;
            }
            $link = $this->documentHelper->getDocumentViewUrl($document);
            if (empty($link)) {
                continue;
            }
            $removeLink = $this->documentHelper->getDocumentRemoveUrl($document);
            $data[] = [
                'name' => $document->getFilename(),
                'url' => '',
                'view_url' => $link,
                'remove_url' => $removeLink,
            ];
        }
        return $data;
    }

    public function getCompany()
    {
        if ($this->_company === null) {
            $customerId = $this->session->getCustomerId();
            $company = $this->companyManagement->getByCustomerId($customerId);
            if ($company === null) {
                $company = $this->companyFactory->create();
            }
            $this->_company = $company;
        }
        return $this->_company;
    }
}
