<?php

namespace Capgemini\BloomreachWidget\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Capgemini\BloomreachWidget\Helper\Data as ModuleHelper;

class Vars implements ArgumentInterface
{
    const BR_RELATED_RID = 'br_related_rid';
    const BR_IUID = 'br_iuid';

    /**
     * @var ModuleHelper
     */
    private $moduleHelper;

    public function __construct(ModuleHelper $moduleHelper)
    {
        $this->moduleHelper = $moduleHelper;
    }

    public function getBrRelatedRid()
    {
        return $this->moduleHelper->getResponseDatum(self::BR_RELATED_RID);
    }

    public function getBrIuid()
    {
        return $this->moduleHelper->getResponseDatum(self::BR_IUID);
    }
}
