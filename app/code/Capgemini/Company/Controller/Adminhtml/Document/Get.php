<?php

namespace Capgemini\Company\Controller\Adminhtml\Document;

use Capgemini\Company\Api\CompanyDocumentRepositoryInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\NoSuchEntityException;

class Get extends \Magento\Backend\App\AbstractAction
{
    const ADMIN_RESOURCE = 'Magento_Company::manage';

    /**
     * @var CompanyDocumentRepositoryInterface
     */
    protected $documentRepository;

    /**
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */
    protected $fileFactory;

    /**
     * Get constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param CompanyDocumentRepositoryInterface $documentRepository
     * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        CompanyDocumentRepositoryInterface $documentRepository,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory
    ) {
        parent::__construct($context);
        $this->documentRepository = $documentRepository;
        $this->fileFactory = $fileFactory;
    }

    public function execute()
    {
        $documentId = $this->getRequest()->getParam('id');
        try {
            $document = $this->documentRepository->getById($documentId);
            $this->documentRepository->loadContents($document);
        } catch (NoSuchEntityException $e) {
            return $this->_error();
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

    protected function _error()
    {
        return $this->resultFactory
            ->create('raw')
            ->setContents('')
            ->setHttpResponseCode(404);
    }
}
