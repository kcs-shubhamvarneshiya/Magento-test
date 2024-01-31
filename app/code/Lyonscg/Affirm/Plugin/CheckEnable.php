<?php

namespace Lyonscg\Affirm\Plugin;

use Astound\Affirm\Model\Config;
use Lyonscg\Affirm\Helper\Data as DataHelper;
use \Lyonscg\Affirm\Helper\Rule as RuleHelper;
use Lyonscg\SalesPad\Helper\Customer;
use Magento\Store\Model\ScopeInterface;

class CheckEnable
{
    /**
     * @var DataHelper
     */
    private $dataModuleHelper;

    /**
     * @var RuleHelper
     */
    private $ruleModuleHelper;

    /**
     * @var bool
     */
    private $cachedResult;

    public function __construct(DataHelper $dataModuleHelper, RuleHelper $ruleModuleHelper)
    {
        $this->dataModuleHelper = $dataModuleHelper;
        $this->ruleModuleHelper = $ruleModuleHelper;
    }

    public function afterGetConfigData(
        Config $subject,
        $result,
        $field,
        $id = null,
        $scope = ScopeInterface::SCOPE_STORE
    ) {
        if ($field !== 'active') {

            return $result;
        }

        return $this->cachedResult ?? $this->getCacheResult($result);
    }

    private function getCacheResult($result)
    {
        $this->cachedResult = ($this->dataModuleHelper->isNeedToHide() || $this->ruleModuleHelper->isNeedToHide()) ? 0 : $result;

        return $this->cachedResult;
    }
}
