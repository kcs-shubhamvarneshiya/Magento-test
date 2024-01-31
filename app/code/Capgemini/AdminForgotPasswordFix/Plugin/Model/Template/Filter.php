<?php

namespace Capgemini\AdminForgotPasswordFix\Plugin\Model\Template;

use Magento\Backend\App\Area\FrontNameResolver;
use \Magento\Email\Model\Template\Filter as Orig;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\Store;

class Filter
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param Orig $subject
     * @param string $result
     * @return string
     */
    public function afterStoreDirective(Orig $subject, string $result): string
    {
        if (!$this->scopeConfig->getValue(FrontNameResolver::XML_PATH_USE_CUSTOM_ADMIN_PATH)) {

            return $result;
        }

        if (!$customPath = $this->scopeConfig->getValue(FrontNameResolver::XML_PATH_CUSTOM_ADMIN_PATH)) {

            return $result;
        }

        $parts = explode('/', $result);
        $adminCodeKey = array_search(Store::ADMIN_CODE, $parts);
        $customPathKey = array_search($customPath, $parts);

        if ($adminCodeKey === false || $adminCodeKey > $customPathKey) {

            return $result;
        }

        unset($parts[$adminCodeKey]);

        return implode('/', $parts);
    }
}
