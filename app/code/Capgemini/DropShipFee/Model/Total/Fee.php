<?php
/**
 * Capgemini_DropShipFee
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\DropShipFee\Model\Total;

use Capgemini\CompanyType\Model\Config;
use Capgemini\DropShipFee\Helper\Data;
use Magento\Customer\Model\Session;
use Magento\Framework\Phrase;
use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address\Total;
use Magento\Quote\Model\Quote\Address\Total\AbstractTotal;
use Magento\Quote\Model\QuoteValidator;
use Magento\Store\Model\Store;

class Fee extends AbstractTotal
{
    public const FEE_CODE = 'drop_ship_fee';
    public const FEE_LABEL = 'Drop Ship Fee';

    /**
     * @var QuoteValidator|null
     */
    protected $quoteValidator = null;

    /**
     * @var Store
     */
    protected $store;

    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var float
     */
    protected $baseDropShipFee;

    /**
     * @var float
     */
    protected $dropShipFee;

    /**
     * @var Config
     */
    protected $companyTypeConfig;

    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * @param Session $customerSession
     * @param Config $companyTypeConfig
     * @param QuoteValidator $quoteValidator
     * @param Data $helper
     */
    public function __construct(
        Session $customerSession,
        Config $companyTypeConfig,
        QuoteValidator $quoteValidator,
        Data $helper
    ) {
        $this->quoteValidator = $quoteValidator;
        $this->helper = $helper;
        $this->companyTypeConfig = $companyTypeConfig;
        $this->customerSession = $customerSession;
    }

    /**
     * @param Quote $quote
     * @param ShippingAssignmentInterface $shippingAssignment
     * @param Total $total
     * @return $this|Fee
     */
    public function collect(
        Quote $quote,
        ShippingAssignmentInterface $shippingAssignment,
        Total $total
    ) {
        parent::collect($quote, $shippingAssignment, $total);
        $customer = $this->customerSession->getCustomer();
        if ($this->companyTypeConfig->getCustomerCompanyType($customer) !== Config::WHOLESALE) {
            return $this;
        }

        $this->store = $quote->getStore();
        if (!$this->helper->isEnable((int)$this->store->getId())) {
            return $this;
        }

        $items = $shippingAssignment->getItems();
        if (!count($items)) {
            return $this;
        }

        $this->baseDropShipFee = 0;
        $this->dropShipFee = 0;

        $values = [];
        foreach ($items as $item) {
            $businessBackendValueId = $item->getProduct()->getData(Data::SPLIT_BY_ATTRIBUTE);
            if ($businessBackendValueId) {
                $values[] = $businessBackendValueId;
            }
        }
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                $uniqCount = count(array_unique($values));
        if ($uniqCount) {
            $feeValue = (float)$this->helper->getAmount((int)$this->store->getId()) * $uniqCount;
            $this->baseDropShipFee = $feeValue;
            $this->dropShipFee = $feeValue;
        }

        $total->setTotalAmount(self::FEE_CODE, $this->dropShipFee);
        $total->setBaseTotalAmount(self::FEE_CODE, $this->dropShipFee);
        $total->setDropShipFee($this->dropShipFee);
        $total->setBaseDropShipFee($this->baseDropShipFee);

        return $this;
    }

    /**
     * @param Quote $quote
     * @param Total $total
     * @return array
     */
    public function fetch(Quote $quote, Total $total)
    {
        return [
            'code' => self::FEE_CODE,
            'title' => self::FEE_LABEL,
            'value' => $this->dropShipFee
        ];
    }

    /**
     * Get Subtotal label
     *
     * @return Phrase
     */
    public function getLabel()
    {
        return __(self::FEE_LABEL);
    }
}
