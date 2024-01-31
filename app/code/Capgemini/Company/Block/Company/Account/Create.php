<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Capgemini\Company\Block\Company\Account;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Action\Action;
use Magento\Newsletter\Model\Config;
use Magento\Customer\Model\AccountManagement;
use Capgemini\Company\Helper\Data as Helper;
use Capgemini\Company\Model\Company\Source\MemberStates;

/**
 * Class Create
 */
class Create extends \Magento\Company\Block\Company\Account\Create
{
    /**
     * @var \Magento\Company\Model\CountryInformationProvider
     */
    private $countryInformationProvider;
    /**
     * @var \Magento\Customer\Helper\Address
     */
    private $addressHelper;

    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $_moduleManager;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var \Capgemini\Company\Model\Config\Source\BusinessType
     */
    protected $_businessTypeSource;

    /**
     * @var \Capgemini\Company\Model\Config\Source\Collection
     */
    protected $collectionSource;

    /**
     * @var Config
     */
    private $newsLetterConfig;

    /**
     * @var Helper
     */
    private $helper;

    /**
     * @var MemberStates
     */
    private $memberStates;

    /**
     * Create constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Company\Model\CountryInformationProvider $countryInformationProvider
     * @param \Magento\Customer\Helper\Address $addressHelper
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param Session $session
     * @param \Capgemini\Company\Model\Config\Source\BusinessType $businessTypeSource
     * @param array $data
     * @param Config|null $newsLetterConfig
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Company\Model\CountryInformationProvider $countryInformationProvider,
        \Magento\Customer\Helper\Address $addressHelper,
        \Magento\Framework\Module\Manager $moduleManager,
        \Magento\Customer\Model\Session $session,
        \Capgemini\Company\Model\Config\Source\BusinessType $businessTypeSource,
        \Capgemini\Company\Model\Config\Source\Collection $collectionSource,
        Helper $helper,
        MemberStates $memberStates,
        array $data = [],
        Config $newsLetterConfig = null
    ) {
        parent::__construct($context, $countryInformationProvider, $addressHelper, $data);
        $this->countryInformationProvider = $countryInformationProvider;
        $this->addressHelper = $addressHelper;
        $this->_moduleManager = $moduleManager;
        $this->_customerSession = $session;
        $this->_businessTypeSource = $businessTypeSource;
        $this->helper = $helper;
        $this->memberStates = $memberStates;
        $this->newsLetterConfig = $newsLetterConfig ?: ObjectManager::getInstance()->get(Config::class);
        $this->collectionSource = $collectionSource;
    }
    /**
     * Newsletter module availability
     *
     * @return bool
     */
    public function isNewsletterEnabled()
    {
        return $this->_moduleManager->isOutputEnabled('Magento_Newsletter')
            && $this->newsLetterConfig->isActive();
    }

    /**
     * Get minimum password length
     *
     * @return string
     * @since 100.1.0
     */
    public function getMinimumPasswordLength()
    {
        return $this->_scopeConfig->getValue(AccountManagement::XML_PATH_MINIMUM_PASSWORD_LENGTH);
    }

    /**
     * Get number of password required character classes
     *
     * @return string
     * @since 100.1.0
     */
    public function getRequiredCharacterClassesNumber()
    {
        return $this->_scopeConfig->getValue(AccountManagement::XML_PATH_REQUIRED_CHARACTER_CLASSES_NUMBER);
    }

    public function isCustomerLoggedIn()
    {
        return $this->_customerSession->isLoggedIn();
    }

    /**
     * @return array
     */
    public function getBusinessTypes()
    {
        return $this->_businessTypeSource->getBusinessTypes();
    }

    /**
     * @return array
     */
    public function getCollections()
    {
        return $this->collectionSource->toArray();
    }

    public function getJsLayout()
    {
        if (!$this->helper->isTaxExemptNotificationEnabled()) {
            $this->jsLayout['components']['companyDocuments']['children']['company-documents-fieldset']['children']['taxExempt']['config']['notice'] = null;
        }
        return parent::getJsLayout();
    }

    /**
     * @return Helper
     */
    public function getHelper()
    {
        return $this->helper;
    }

    /**
     * @param $selected
     * @return array
     */
    public function getMemberStateOptions()
    {
        return $this->memberStates->toOptionArray();
    }
}
