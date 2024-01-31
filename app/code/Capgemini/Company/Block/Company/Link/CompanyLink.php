<?php

namespace Capgemini\Company\Block\Company\Link;

class CompanyLink extends \Magento\Company\Block\Link\Current
{
    /**
     * @var \Magento\Company\Model\CompanyContext
     */
    private $companyContext;

    /**
     * @var \Magento\Company\Api\CompanyManagementInterface
     */
    private $companyManagement;

    /**
     * CompanyLink constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\App\DefaultPathInterface $defaultPath
     * @param \Magento\Company\Model\CompanyContext $companyContext
     * @param \Magento\Company\Api\CompanyManagementInterface $companyManagement
     * @param array $data [optional]
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\DefaultPathInterface $defaultPath,
        \Magento\Company\Model\CompanyContext $companyContext,
        \Magento\Company\Api\CompanyManagementInterface $companyManagement,
        array $data = []
    ) {
        parent::__construct($context, $defaultPath, $companyContext, $data);
        $this->companyContext = $companyContext;
        $this->companyManagement = $companyManagement;
    }

    /**
     * @inheritdoc
     */
    protected function isVisible()
    {
        $company = null;
        if ($this->companyContext->getCustomerId()) {
            $company = $this->companyManagement->getByCustomerId($this->companyContext->getCustomerId());
        }
        // if there is no company, don't show the link
        if (!$company) {
            return false;
        }
        // if there is a company, check permissions as normal
        return parent::isVisible();
    }
}
