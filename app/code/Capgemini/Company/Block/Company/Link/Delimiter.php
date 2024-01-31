<?php

namespace Capgemini\Company\Block\Company\Link;

class Delimiter extends \Magento\Framework\View\Element\Template
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
     * @var array
     */
    private $resources;

    /**
     * CompanyLink constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Company\Model\CompanyContext $companyContext
     * @param \Magento\Company\Api\CompanyManagementInterface $companyManagement
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Company\Model\CompanyContext $companyContext,
        \Magento\Company\Api\CompanyManagementInterface $companyManagement,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->companyContext = $companyContext;
        $this->companyManagement = $companyManagement;
        $this->resources = isset($data['resources']) && is_array($data['resources'])
            ? array_values($data['resources'])
            : [];
    }

    /**
     * Return HTML only if block is visible.
     *
     * @return string
     */
    protected function _toHtml()
    {
        if ($this->isVisible()) {
            return parent::_toHtml();
        }

        return '';
    }

    /**
     * Determine if the block is visible.
     *
     * @return bool
     */
    private function isVisible()
    {
        $company = null;
        if ($this->companyContext->getCustomerId()) {
            $company = $this->companyManagement->getByCustomerId($this->companyContext->getCustomerId());
        }
        // no company, don't show this delimiter
        if (!$company) {
            return false;
        }

        // otherwise visible if resource(s) are allowed
        $isVisible = false;
        $isActive = $this->companyContext->isModuleActive();
        if ($isActive) {
            $isVisible = $this->isResourceAllowed();
        }

        return $isVisible;
    }

    /**
     * Determine if any of assigned resources is allowed.
     *
     * @return bool
     */
    private function isResourceAllowed()
    {
        $result = false;
        foreach ($this->resources as $resource) {
            if ($this->companyContext->isResourceAllowed($resource) === true) {
                $result = true;
                break;
            }
        }

        return $result;
    }
}
