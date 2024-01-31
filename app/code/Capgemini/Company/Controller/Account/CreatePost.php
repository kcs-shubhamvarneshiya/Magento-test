<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Capgemini\Company\Controller\Account;

use Magento\Framework\Exception\InputException;

/**
 * Create company account action.
 */
class CreatePost extends \Magento\Company\Controller\Account\CreatePost
{
    /**
     * @var string
     */
    private $formId = 'company_create';

    /**
     * @var \Magento\Framework\Api\DataObjectHelper
     */
    private $objectHelper;

    /**
     * @var \Magento\Framework\Data\Form\FormKey\Validator
     */
    private $formKeyValidator;

    /**
     * @var \Magento\Company\Model\Action\Validator\Captcha
     */
    private $captchaValidator;

    /**
     * @var \Magento\Authorization\Model\UserContextInterface
     */
    private $userContext;

    /**
     * @var \Magento\Customer\Api\AccountManagementInterface
     */
    private $customerAccountManagement;

    /**
     * @var \Magento\Customer\Api\Data\CustomerInterfaceFactory
     */
    private $customerDataFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var \Magento\Company\Model\Create\Session
     */
    private $companyCreateSession;

    /**
     * @var \Capgemini\Company\Helper\CustomerAddress
     */
    private $addressHelper;

    /**
     * @var \Magento\Framework\Serialize\SerializerInterface
     */
    private $serializer;

    /**
     * CreatePost constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Authorization\Model\UserContextInterface $userContext
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Api\DataObjectHelper $objectHelper
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     * @param \Magento\Company\Model\Action\Validator\Captcha $captchaValidator
     * @param \Magento\Customer\Api\AccountManagementInterface $customerAccountManagement
     * @param \Magento\Customer\Api\Data\CustomerInterfaceFactory $customerDataFactory
     * @param \Magento\Company\Model\Create\Session $companyCreateSession
     * @param \Capgemini\Company\Helper\CustomerAddress $addressHelper
     * @param \Magento\Framework\Serialize\SerializerInterface $serializer
     * * @param \Magento\Company\Model\CompanyUser|null $companyUser
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Authorization\Model\UserContextInterface $userContext,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Api\DataObjectHelper $objectHelper,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        \Magento\Company\Model\Action\Validator\Captcha $captchaValidator,
        \Magento\Customer\Api\AccountManagementInterface $customerAccountManagement,
        \Magento\Customer\Api\Data\CustomerInterfaceFactory $customerDataFactory,
        \Magento\Company\Model\Create\Session $companyCreateSession,
        \Capgemini\Company\Helper\CustomerAddress $addressHelper,
        \Magento\Framework\Serialize\SerializerInterface $serializer,
        \Magento\Company\Model\CompanyUser $companyUser = null
    ) {
        parent::__construct($context, $userContext, $logger, $objectHelper, $formKeyValidator,
            $captchaValidator, $customerAccountManagement, $customerDataFactory, $companyCreateSession,$companyUser);
        $this->logger = $logger;
        $this->objectHelper = $objectHelper;
        $this->formKeyValidator = $formKeyValidator;
        $this->captchaValidator = $captchaValidator;
        $this->customerAccountManagement = $customerAccountManagement;
        $this->customerDataFactory = $customerDataFactory;
        $this->companyCreateSession = $companyCreateSession;
        $this->addressHelper = $addressHelper;
        $this->serializer = $serializer;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $request = $this->getRequest();
        $resultRedirect = $this->resultRedirectFactory->create()->setPath('*/account/create');

        if (!$this->validateRequest()) {
            return $resultRedirect;
        }

        try {
            $customer = $this->customerDataFactory->create();
            $customerData = $request->getPost('customer', []);
            $companyData = $request->getParam('company', false);
            $customerData['custom_attributes'] = [
                [
                    "attribute_code" => "customer_company_name",
                    "value" => $companyData['company_name'] ?? ""
                ]
            ];
            $password = $this->getRequest()->getParam('password');
            $confirmation = $this->getRequest()->getParam('password_confirmation');
            $this->checkPasswordConfirmation($password, $confirmation);

            $extensionAttributes = $customer->getExtensionAttributes();
            $extensionAttributes->setIsSubscribed($this->getRequest()->getParam('is_subscribed', false));
            $customer->setExtensionAttributes($extensionAttributes);

            $this->objectHelper->populateWithArray(
                $customer,
                $customerData,
                \Magento\Customer\Api\Data\CustomerInterface::class
            );
            $isCustomAndCompSuccessCreated = false;
            $customer = $this->customerAccountManagement->createAccount($customer, $password);
            $isCustomAndCompSuccessCreated = true;

            $address = $this->addressHelper->createCustomerAddress($customer, 'company', 'company');
            if (!$this->addressHelper->syncCustomerAddress($customer, $address)) {
                $this->logger->debug(sprintf($this->addressHelper::SYNC_ERROR_PATTERN, $customer->getemail()));
            }

            $this->companyCreateSession->setCustomerId($customer->getId());
            $this->messageManager->addSuccessMessage(
                __('Thank you! We\'re reviewing your request and will contact you soon')
            );
            $resultRedirect->setPath('*/account/index');
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(
                __('An error occurred on the server. Your changes have not been saved.')
            );
            $this->logger->critical($e);

            if (isset($isCustomAndCompSuccessCreated) and $isCustomAndCompSuccessCreated === false) {
                $this->logger->warning(
                    'Company might be created not fully',
                    ['companyData' => $companyData ?? []]
                );
            }
        }

        return $resultRedirect;
    }

    /**
     * Make sure that password and password confirmation matched
     *
     * @param string $password
     * @param string $confirmation
     * @return void
     * @throws InputException
     */
    protected function checkPasswordConfirmation($password, $confirmation)
    {
        if ($password != $confirmation) {
            throw new InputException(__('Please make sure your passwords match.'));
        }
    }

    /**
     * Validate request
     *
     * @return bool
     */
    private function validateRequest()
    {
        if (!$this->getRequest()->isPost()) {
            return false;
        }

        if (!$this->formKeyValidator->validate($this->getRequest())) {
            return false;
        }

        if (!$this->captchaValidator->validate($this->formId, $this->getRequest())) {
            $this->messageManager->addErrorMessage(__('Incorrect CAPTCHA'));
            return false;
        }

        if (!$this->validateTradeDocuments()) {
            $this->messageManager->addErrorMessage(__('Proof of Trade Documents are ether missing or at least one of them contains errors.'));
            return false;
        }

        return true;
    }

    private function validateTradeDocuments()
    {
        $companyData = $this->getRequest()->getParam('company');

        if (!isset($companyData['proof'])) {

            return false;
        }

        $docData = $this->serializer->unserialize($companyData['proof']);
        foreach ($docData as $docDatum) {
            if (!isset($docDatum['path']) || !isset($docDatum['file'])) {

                return false;
            }

            $filePath = $docDatum['path'] . '/' . $docDatum['file'];

            if (!file_exists($filePath) || !file_get_contents($filePath)) {

                return false;
            }
        }


        return true;
    }
}
