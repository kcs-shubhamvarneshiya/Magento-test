<?php

namespace Capgemini\Company\Controller\Account;

use Capgemini\Company\Helper\Document as DocumentHelper;
use Capgemini\Company\Model\ResourceModel\Company\Document\Contents;
use Magento\Company\Api\CompanyManagementInterface;
use Magento\Company\Api\Data\CompanyInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\MediaStorage\Model\File\Uploader;
use Magento\MediaStorage\Model\File\UploaderFactory;

// TODO - add company management, customer session, code to save document (see the after plugin)

class UploadDocument extends \Magento\Framework\App\Action\Action implements HttpPostActionInterface
{
    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var UploaderFactory
     */
    protected $uploaderFactory;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var DocumentHelper
     */
    protected $documentHelper;

    /**
     * @var CompanyManagementInterface
     */
    protected $companyManagement;

    /**
     * @var string[]
     */
    protected $fileInputs = [
        'proofOfTrade',
        'taxExempt'
    ];

    protected $documentTypes = [
        'proofOfTrade' => 'proof',
        'taxExempt' => 'tax-exempt'
    ];

    protected $allowedExtensions = [
        'jpg', 'jpeg', 'png', 'pdf'
    ];

    /**
     * UploadDocument constructor.
     * @param Context $context
     * @param Filesystem $filesystem
     * @param UploaderFactory $uploaderFactory
     * @param Session $session
     * @param DocumentHelper $documentHelper
     * @param CompanyManagementInterface $companyManagement
     */
    public function __construct(
        Context $context,
        Filesystem $filesystem,
        UploaderFactory $uploaderFactory,
        Session $session,
        DocumentHelper $documentHelper,
        CompanyManagementInterface $companyManagement
    ) {
        parent::__construct($context);
        $this->filesystem = $filesystem;
        $this->uploaderFactory = $uploaderFactory;
        $this->session = $session;
        $this->documentHelper = $documentHelper;
        $this->companyManagement = $companyManagement;
    }

    public function execute()
    {
        $result = false;
        foreach ($this->fileInputs as $fileInputName) {
            if (!isset($_FILES[$fileInputName])) {
                continue;
            }
            $newFileName = $this->_getNewFileName($_FILES[$fileInputName]);
            $destPath = $this->_getDestinationPath($fileInputName);
            $uploader = $this->uploaderFactory->create(['fileId' => $fileInputName])
                ->setAllowCreateFolders(true)
                ->setAllowedExtensions($this->allowedExtensions)
                ->addValidateCallback('validate', $this, 'validateFile');
            $result = $uploader->save($destPath, $newFileName);
            if (!$result) {
                throw new LocalizedException(
                    __('File cannot be saved to: $1', $destPath)
                );
            }
            break;
        }
        $result['tmp_name'] = ltrim($result['file'], '/');
        $result['url'] = '';

        $result = $this->_saveResult($fileInputName, $result, $newFileName);
        $result['real_file_name'] = $newFileName;
        if (isset($reuslt['tmp_name'])) {
            unset($result['tmp_name']);
        }
        if (isset($reuslt['file'])) {
            unset($result['file']);
        }
        if (isset($reuslt['path'])) {
            unset($result['path']);
        }
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $resultJson->setData($result);
        return $resultJson;
    }

    /**
     * @param $fileInputName
     * @return string
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    protected function _getDestinationPath($fileInputName)
    {
        return $this->filesystem
            ->getDirectoryWrite(DirectoryList::VAR_DIR)
            ->getAbsolutePath('/'. Contents::MAIN_SAVE_DIRECTORY . '/tmp');
    }

    /**
     * @param $filePath
     * @return bool
     */
    public function validateFile($filePath)
    {
        // TODO - update this if we want ot add file validation
        return true;
    }

    /**
     * @param $fileData
     * @return string
     */
    protected function _getNewFileName($fileData)
    {
        $str = implode('-', $fileData);
        // salt the name just in case predicting the file names could cause problems
        $str .= mt_rand();
        return md5($str);
    }

    /**
     * @return CompanyInterface
     */
    protected function _getCompany()
    {
        if (!$this->session->isLoggedIn()) {
            return null;
        }

        try {
            $customerId = $this->session->getCustomerId();
            return $this->companyManagement->getByCustomerId($customerId);
        } catch (NoSuchEntityException $e) {
            return null;
        }
    }

    /**
     * @param $type
     * @param array $result
     * @param $realFileName
     * @return array
     */
    protected function _saveResult($type, array $result, $realFileName)
    {
        $company = $this->_getCompany();
        if ($company && $company->getId()) {
            $document = $this->_saveDocument($company, $type, $result, $realFileName);
            if ($document !== false) {
                $viewUrl = $this->documentHelper->getDocumentViewUrl($document);
                $removeUrl = $this->documentHelper->getDocumentRemoveUrl($document);
                if ($viewUrl) {
                    $result['view_url'] = $viewUrl;
                }
                if ($removeUrl) {
                    $result['remove_url'] = $removeUrl;
                }
            }
        } else {
//            $this->_saveResultToSession($type, $result, $realFileName);
        }
        return $result;
    }

    /**
     * @param $type
     * @param array $result
     * @param $realFileName
     */
    protected function _saveResultToSession($type, array $result, $realFileName)
    {
        $result['real_file_name'] = $realFileName;
        $data = $this->session->getCompanyDocuments();
        if (!$data) {
            $data = [];
        }
        if (!isset($data[$type])) {
            $data[$type] = [];
        }
        $data[$type][] = $result;
        $this->session->setCompanyDocuments($data);
        unset($data['real_file_name']);
    }

    /**
     * @param CompanyInterface $company
     * @param $type
     * @param array $result
     * @param $realFileName
     */
    protected function _saveDocument(CompanyInterface $company, $type, array $result, $realFileName)
    {
        $email = $this->session->getCustomer()->getEmail();
        $realFileName = $result['path'] . '/' . $realFileName;
        $name = $result['name'];
        $type = $this->documentTypes[$type];
        return $this->documentHelper->createDocument($realFileName, $email, $name, $type, $company->getId());
    }
}
