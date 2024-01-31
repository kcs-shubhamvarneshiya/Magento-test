<?php

namespace Capgemini\Company\Controller\Account;

use Capgemini\Company\Api\CompanyDocumentRepositoryInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Company\Api\CompanyManagementInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\NoSuchEntityException;

class GetDocument extends \Magento\Framework\App\Action\Action implements HttpGetActionInterface
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
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */
    protected $fileFactory;

    /**
     * @var \Magento\Framework\Controller\Result\ForwardFactory
     */
    private $forwardFactory;

    /**
     * GetDocument constructor.
     * @param Context $context
     * @param CompanyDocumentRepositoryInterface $documentRepository
     * @param CompanyManagementInterface $companyManagement
     * @param CustomerSession $customerSession
     * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory
     * @param \Magento\Framework\Controller\Result\ForwardFactory $forwardFactory
     */
    public function __construct(
        Context $context,
        CompanyDocumentRepositoryInterface $documentRepository,
        CompanyManagementInterface $companyManagement,
        CustomerSession $customerSession,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\Controller\Result\ForwardFactory $forwardFactory
    ) {
        parent::__construct($context);
        $this->documentRepository = $documentRepository;
        $this->companyManagement = $companyManagement;
        $this->customerSession = $customerSession;
        $this->fileFactory = $fileFactory;
        $this->forwardFactory = $forwardFactory;
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
        $companyDocuments = $this->documentRepository->getForCompany($company);
        $documentId = $this->getRequest()->getParam('document_id');
        if (!$companyDocuments->getItemById($documentId)) {
            $resultForward = $this->forwardFactory->create();
            $resultForward->setController('index');
            $resultForward->forward('defaultNoRoute');
            return $resultForward;
        }
        try {
            $document = $this->documentRepository->getById($documentId);
            $this->documentRepository->loadContents($document);
        } catch (NoSuchEntityException $e) {
            return $this->_error(__('Requested document does not exist'));
        }
        if (!$document->getContents()) {
            try {
                $this->documentRepository->delete($document);
            } catch (\Exception $e) {
                // do nothing
            }
            return $this->_error(__('There document "%1" did not upload properly', $document->getFilename()));
        }
        // send file
        $this->fileFactory->create(
            $document->getFilename(),
            [
                'type' => 'string',
                'value' => $document->getContents(),
                'rm' => true
            ],
            DirectoryList::VAR_DIR
        );
    }

    protected function _error($message)
    {
        return $this->resultFactory
            ->create('raw')
            ->setContents('<html><body><p>' . $message . '</p></body></html>')
            ->setHttpResponseCode(404);
    }
}
