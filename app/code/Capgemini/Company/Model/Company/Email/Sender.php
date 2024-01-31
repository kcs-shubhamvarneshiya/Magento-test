<?php

namespace Capgemini\Company\Model\Company\Email;

use Capgemini\Company\Helper\Document as DocumentHelper;
use Magento\Company\Api\CompanyRepositoryInterface;
use Magento\Company\Model\Config\EmailTemplate as EmailTemplateConfig;
use Magento\Company\Model\Email\CustomerData;
use Magento\Company\Model\Email\Transporter;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Api\CustomerNameGenerationInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class Sender extends \Magento\Company\Model\Email\Sender
{
    /**
     * Email template for identity.
     */
    private $xmlPathRegisterEmailIdentity = 'customer/create_account/email_identity';

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var \Magento\Company\Model\Email\Transporter
     */
    private $transporter;

    /**
     * @var \Magento\Customer\Api\CustomerNameGenerationInterface
     */
    private $customerViewHelper;

    /**
     * @var \Magento\Company\Model\Email\CustomerData
     */
    private $customerData;

    /**
     * @var \Magento\Company\Model\Config\EmailTemplate
     */
    private $emailTemplateConfig;

    /**
     * @var \Magento\Company\Api\CompanyRepositoryInterface
     */
    private $companyRepository;

    private $documentHelper;


    /**
     * @param StoreManagerInterface $storeManager
     * @param ScopeConfigInterface $scopeConfig
     * @param Transporter $transporter
     * @param CustomerNameGenerationInterface $customerViewHelper
     * @param CustomerData $customerData
     * @param EmailTemplateConfig $emailTemplateConfig
     * @param CompanyRepositoryInterface $companyRepository
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig,
        Transporter $transporter,
        CustomerNameGenerationInterface $customerViewHelper,
        CustomerData $customerData,
        EmailTemplateConfig $emailTemplateConfig,
        CompanyRepositoryInterface $companyRepository,
        DocumentHelper $documentHelper
    ) {
        parent::__construct(
            $storeManager,
            $scopeConfig,
            $transporter,
            $customerViewHelper,
            $customerData,
            $emailTemplateConfig,
            $companyRepository
        );
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->transporter = $transporter;
        $this->customerViewHelper = $customerViewHelper;
        $this->customerData = $customerData;
        $this->emailTemplateConfig = $emailTemplateConfig;
        $this->companyRepository = $companyRepository;
        $this->documentHelper = $documentHelper;
    }

    /**
     * Get either first store ID from a set website or the provided as default.
     *
     * @param CustomerInterface $customer
     * @return int
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function getWebsiteStoreId(CustomerInterface $customer)
    {
        $defaultStoreId = \Magento\Store\Model\Store::DEFAULT_STORE_ID;
        if ($customer->getWebsiteId() != 0) {
            $storeIds = $this->storeManager->getWebsite($customer->getWebsiteId())->getStoreIds();
            reset($storeIds);
            $defaultStoreId = current($storeIds);
        }
        return $defaultStoreId;
    }

    /**
     * Notify admin about new company.
     *
     * @param CustomerInterface $customer
     * @param string $companyName
     * @param string $companyUrl
     * @return $this
     */
    public function sendAdminNotificationEmail(CustomerInterface $customer, $companyName, $companyUrl)
    {
        $toCode = $this->emailTemplateConfig->getCompanyCreateRecipient(ScopeInterface::SCOPE_STORE);
        $toEmail = $this->scopeConfig->getValue('trans_email/ident_' . $toCode . '/email', ScopeInterface::SCOPE_STORE);
        $toName = $this->scopeConfig->getValue('trans_email/ident_' . $toCode . '/name', ScopeInterface::SCOPE_STORE);

        $copyTo = $this->emailTemplateConfig->getCompanyCreateCopyTo(ScopeInterface::SCOPE_STORE);
        $copyMethod = $this->emailTemplateConfig->getCompanyCreateCopyMethod(ScopeInterface::SCOPE_STORE);
        $storeId = $customer->getStoreId() ?: $this->getWebsiteStoreId($customer);

        $hasDocuments = $this->documentHelper->customerCompanyHasDocuments($customer);

        $sendTo = [];
        if ($copyTo && $copyMethod == 'copy') {
            $sendTo = explode(',', $copyTo);
        }
        array_unshift($sendTo, $toEmail);

        foreach ($sendTo as $recipient) {
            $this->sendEmailTemplate(
                $recipient,
                $toName,
                $this->emailTemplateConfig->getCompanyCreateNotifyAdminTemplateId(),
                [
                    'email' => $toEmail,
                    'name' => $toName
                ],
                [
                    'customer' => $customer->getFirstname(),
                    'company' => $companyName,
                    'admin' => $toName,
                    'company_url' => $companyUrl,
                    'has_documents' => $hasDocuments
                ],
                $storeId,
                ($copyTo && $copyMethod == 'bcc') ? explode(',', $copyTo) : []
            );
        }

        return $this;
    }

    /**
     * Send corresponding email template.
     *
     * @param string $customerEmail
     * @param string $customerName
     * @param string $templateId
     * @param string|array $sender configuration path of email identity
     * @param array $templateParams [optional]
     * @param int|null $storeId [optional]
     * @param array $bcc [optional]
     * @return void
     */
    private function sendEmailTemplate(
        $customerEmail,
        $customerName,
        $templateId,
        $sender,
        array $templateParams = [],
        $storeId = null,
        array $bcc = []
    ) {
        $from = $sender;
        if (is_string($sender)) {
            $from = $this->scopeConfig->getValue($sender, ScopeInterface::SCOPE_STORE, $storeId);
        }
        $this->transporter->sendMessage(
            $customerEmail,
            $customerName,
            $from,
            $templateId,
            $templateParams,
            $storeId,
            $bcc
        );
    }
}
