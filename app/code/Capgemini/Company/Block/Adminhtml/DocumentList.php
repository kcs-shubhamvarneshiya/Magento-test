<?php

namespace Capgemini\Company\Block\Adminhtml;

use Capgemini\Company\Model\ResourceModel\Company\Document\Collection;
use Capgemini\Company\Model\ResourceModel\Company\Document\CollectionFactory;
use Magento\Framework\Exception\NoSuchEntityException;

class DocumentList extends \Magento\Backend\Block\Template
{
    protected $_template = 'Capgemini_Company::document-list.phtml';

    /**
     * @return \Capgemini\Company\Model\Company\Document[]
     */
    protected $documents = [];

    /**
     * @var \Magento\Company\Api\CompanyRepositoryInterface
     */
    protected $companyRepository;

    /**
     * @var CollectionFactory
     */
    protected $documentsCollectionFactory;

    /**
     * DocumentList constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Company\Api\CompanyRepositoryInterface $companyRepository
     * @param CollectionFactory $documentsCollectionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Company\Api\CompanyRepositoryInterface $companyRepository,
        CollectionFactory $documentsCollectionFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->companyRepository = $companyRepository;
        $this->documentsCollectionFactory = $documentsCollectionFactory;
    }

    /**
     * @return \Capgemini\Company\Model\Company\Document[]
     */
    protected function getDocuments()
    {
        $companyId = $this->getRequest()->getParam('id');
        if ($companyId && empty($this->documents)) {
            try {
                $company = $this->companyRepository->get($companyId);
                /** @var Collection $collection */
                $collection = $this->documentsCollectionFactory->create();
                $collection->addCompanyFilter($company);
                $this->documents = $collection->getItems();
            } catch (NoSuchEntityException $e) {
                $this->documents = [];
            }
        }
        return $this->documents;
    }

    public function getDocumentLinks()
    {
        $links = [];
        foreach ($this->getDocuments() as $document) {
            $links[] = [
                'id' => $document->getId(),
                'url' => $this->getUrl('company_documents/document/get', ['id' => $document->getId()]),
                'filename' => $document->getFilename(),
                'type' => $document->getDocumentType(),
            ];
        }
        return $links;
    }
}
