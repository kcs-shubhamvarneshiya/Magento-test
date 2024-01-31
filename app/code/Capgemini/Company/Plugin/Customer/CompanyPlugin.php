<?php

namespace Capgemini\Company\Plugin\Customer;

use Capgemini\Company\Helper\Document as DocumentHelper;
use Magento\Company\Api\Data\CompanyInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Company\Model\Customer\Company;
use Magento\Customer\Api\Data\CustomerInterface;
use Psr\Log\LoggerInterface;

class CompanyPlugin
{
    /**
     * @var DocumentHelper
     */
    protected $documentHelper;

    protected $documentTypes = [
        'proofOfTrade' => 'proof',
        'taxExempt' => 'tax-exempt'
    ];
    /**
     * @var RequestInterface
     */
    private $request;
    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * CompanyPlugin constructor.
     * @param DocumentHelper $documentHelper
     * @param RequestInterface $request
     * @param SerializerInterface $serializer
     * @param LoggerInterface $logger
     */
    public function __construct(
        DocumentHelper $documentHelper,
        RequestInterface $request,
        SerializerInterface $serializer,
        LoggerInterface $logger
    ) {
        $this->documentHelper = $documentHelper;
        $this->request = $request;
        $this->serializer = $serializer;
        $this->logger = $logger;
    }

    /**
     * @param Company $subject
     * @param CustomerInterface $customer
     * @param array $companyData
     * @param null $jobTitle
     * @return array
     */
    public function beforeCreateCompany(Company $subject, CustomerInterface $customer, array $companyData, $jobTitle = null)
    {
        if (isset($companyData['business_type']) && trim($companyData['business_type']) === 'other') {
            if (isset($companyData['business_type_other'])) {
                $companyData['business_type'] = $companyData['business_type_other'];
            }
        }
        return [$customer, $companyData, $jobTitle];
    }

    /**
     * @param Company $subject
     * @param CompanyInterface $result
     * @return CompanyInterface|void
     */
    public function afterCreateCompany(Company $subject, CompanyInterface $result)
    {
        try {
            $companyId = $result->getId();
            if (!$companyId) {
                return;
            }

            $email = $result->getCompanyEmail();
            foreach ($this->documentTypes as $inputName => $documentType) {
                if (!isset($result[$documentType])) {
                    continue;
                }
                foreach ($this->serializer->unserialize($result[$documentType]) as $document) {
                    $this->_createDocument($email, $document, $documentType, $companyId);
                }
            }
        } catch (\Exception $e) {
            $this->logger->error("Save company documents error: " . $e->getMessage());
        }
        return $result;
    }

    /**
     * @param $email
     * @param $document
     * @param $documentType
     * @param $companyId
     * @return bool
     */
    protected function _createDocument($email, $document, $documentType, $companyId)
    {
        // name of file on the server
        $realFileName = $document['path'] . '/' . $document['real_file_name'];
        // actual name of the file
        $fileName = $document['name'];
        return $this->documentHelper->createDocument($realFileName, $email, $fileName, $documentType, $companyId);
    }
}
