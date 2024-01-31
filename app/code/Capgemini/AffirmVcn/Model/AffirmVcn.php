<?php
namespace Capgemini\AffirmVcn\Model;

use Astound\Affirm\Model\Ui\ConfigProvider;
use Capgemini\Payfabric\Model\Payfabric;
use Magento\Store\Model\Store;


class AffirmVcn extends Payfabric
{
    const METHOD_CODE = 'affirm_vcn';

    const PAYFABRIC_SYNC_CONFIG_FIELDS = [
        'transaction_mode',
        'device_id',
        'device_password',
        'setup_id',
        'cctypes',
        'payment_action',
        'debug',
        'order_status',
        'sort_order',
        'enable_detailed_errors',
        'error_code',
        'default_payment_error'
    ];

    const AFFIRM_GATEWAY_SYNC_CONFIG_FIELDS = [
        'active'
    ];

    protected $_code = self::METHOD_CODE;

    protected $_infoBlockType = \Magento\Payment\Block\Info::class;

    /**
     * Retrieve information from payment configuration
     * of the correct payment method.
     *
     * @param string $field
     * @param int|string|null|Store $storeId
     *
     * @return mixed
     */
    public function getConfigData($field, $storeId = null)
    {
        if (in_array($field, self::PAYFABRIC_SYNC_CONFIG_FIELDS)) {
            $this->_code = Payfabric::METHOD_CODE;
        } elseif (in_array($field, self::AFFIRM_GATEWAY_SYNC_CONFIG_FIELDS)) {
            $this->_code = ConfigProvider::CODE;
        }

        $parent = parent::getConfigData($field, $storeId);
        $this->_code = self::METHOD_CODE;

        return $parent;
    }
}
