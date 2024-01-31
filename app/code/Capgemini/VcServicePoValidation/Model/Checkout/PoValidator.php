<?php
/**
 * Capgemini_VcServicePoValidation
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\VcServicePoValidation\Model\Checkout;

use Capgemini\OrderSplit\Api\Checkout\Validator\ValidatorInterface;
use Capgemini\VcServicePoValidation\Service\PoValidation;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Phrase;

/**
 * Checkout PO Validator
 */
class PoValidator implements ValidatorInterface
{
    public const ENABLED_CONFIG_PATH = 'po_validation/client/enabled';

    /**
     * @var PoValidation
     */
    protected $client;

    /**
     * @var Phrase
     */
    protected $errorMessage;
    /**
     * @var CustomerSession
     */
    protected $customerSession;
    protected ScopeConfigInterface $scopeConfig;

    public function __construct(
        PoValidation $client,
        CustomerSession $customerSession,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->client = $client;
        $this->customerSession = $customerSession;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Validate PO number
     *
     * @param array $fields [0 => {PO number}]
     * @return bool
     */
    public function validate(array $fields): bool
    {
        $result = true;
        if ($this->scopeConfig->getValue(self::ENABLED_CONFIG_PATH)) {
            $poNumber = $fields[0];
            $customer = $this->customerSession->getCustomer();
            if ($customer) {
                $vcNumber = $customer->getData('customer_number_vc');
            }

            if ($vcNumber) {
                try {
                    $result = $this->client->validate($vcNumber, $poNumber);
                    if ($result) {
                        $this->errorMessage = null;
                    } else {
                        $this->errorMessage = __('Invalid PO number.');
                    }
                } catch (\Exception $e) {
                    $result = true;
                    $this->errorMessage = __('Something went wrong during validation.');
                }
            }
        }

        return $result;
    }

    /**
     * Get error message
     *
     * @return Phrase
     */
    public function getErrorMessage(): Phrase
    {
        return $this->errorMessage;
    }
}