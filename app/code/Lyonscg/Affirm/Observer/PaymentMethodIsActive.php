<?php

namespace Lyonscg\Affirm\Observer;

use Astound\Affirm\Model\Ui\ConfigProvider;
use Magento\Framework\DataObject;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Payment\Model\Method\Adapter;
use Lyonscg\Affirm\Helper\Data as DataHelper;
use \Lyonscg\Affirm\Helper\Rule as RuleHelper;

class PaymentMethodIsActive implements ObserverInterface
{
    /**
     * @var bool
     */
    private $shouldMakeNotAvailable = false;

    public function __construct(DataHelper $dataModuleHelper, RuleHelper $ruleModuleHelper)
    {
        $this->shouldMakeNotAvailable = $dataModuleHelper->isNeedToHide() || $ruleModuleHelper->isNeedToHide();
    }

    public function execute(Observer $observer)
    {
        if (!$this->shouldMakeNotAvailable) {

            return;
        }

        /** @var Adapter $paymentMethod */
        if ($observer->getEvent()->getData('method_instance')->getCode() !== ConfigProvider::CODE) {

            return;
        }

        /** @var DataObject $result */
        $result = $observer->getEvent()->getData('result');
        $result->setData('is_available', false);
    }
}
