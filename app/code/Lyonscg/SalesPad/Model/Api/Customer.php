<?php

namespace Lyonscg\SalesPad\Model\Api;

use Capgemini\Company\Model\Config\Source\BusinessType;
use Lyonscg\SalesPad\Helper\Customer as CustomerHelper;
use Lyonscg\SalesPad\Model\Api;
use Lyonscg\SalesPad\Model\Config as SalesPadConfig;
use Lyonscg\SalesPad\Model\CustomerNumResolver;
use Magento\Customer\Api\Data\CustomerInterface;

class Customer
{
    /**
     * Prevent failure data from stacking up too much
     */
    const FAILURE_MAX = 20;

    const ACTION_CREATE = 'api/Customer';
    const ACTION_UPDATE = 'api/Customer/%s';
    const ACTION_GET    = 'api/Customer/%s';
    const ACTION_SEARCH = 'api/CustomerSearch%s';

    const CUSTOMER_NUM = 'Customer_Num';

    /**
     * @var \Lyonscg\SalesPad\Model\Api
     */
    protected $api;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var CustomerHelper
     */
    protected $customerHelper;

    /**
     * @var BusinessType
     */
    protected $businessTypeSource;

    /**
     * @var CustomerNumResolver
     */
    protected $customerNumResolver;

    /**
     * @var SalesPadConfig
     */
    protected $config;

    /**
     * @var array
     */
    protected $failures = [];

    /**
     * Customer constructor.
     * @param Api $api
     * @param Logger $logger
     * @param CustomerHelper $customerHelper
     * @param BusinessType $businessTypeSource
     * @param CustomerNumResolver $customerNumResolver
     * @param SalesPadConfig $config
     */
    public function __construct(
        Api $api,
        \Lyonscg\SalesPad\Model\Api\Logger $logger,
        CustomerHelper $customerHelper,
        BusinessType $businessTypeSource,
        CustomerNumResolver $customerNumResolver,
        SalesPadConfig $config
    ) {
        $this->api = $api;
        $this->logger = $logger;
        $this->customerHelper = $customerHelper;
        $this->businessTypeSource = $businessTypeSource;
        $this->customerNumResolver = $customerNumResolver;
        $this->config = $config;
    }

    /**
     * @param $customerNum
     * @return bool|mixed
     */
    public function get($customerNum)
    {
        $action = sprintf(self::ACTION_GET, strval($customerNum));
        try {
            $response = $this->api->callApi($action, Api::GET);
            if (!$response) {
                $this->logger->debug("Call to $action failed to return response object");
                return false;
            }
            $responseCode = $response->getStatusCode();
            if ($responseCode == 200 || $responseCode == 201) {
                return $this->api->extractJson($response);
            } else {
                $this->logger->debug(
                    "API call $action returned status code $responseCode"
                );
            }
        } catch (\Exception $e) {
            return false;
        }
        return false;
    }

    /**
     * @param CustomerInterface $customer
     * @return bool|mixed
     */
    public function update(CustomerInterface $customer)
    {
        if (!$this->config->getCustomerSyncUpdateEnabled()) {
            return true;
        }

        $this->clearFailures();
        // CRC-638 Moved sales_pad_customer_num customer attribute from a custom to an extension one.
        $customerNum = $customer->getExtensionAttributes()->getSalesPadCustomerNum();

        // if this customer belongs to a company and is not the company admin, then do not update
        // because all company customers share the same sales pad number
        $company = $this->customerHelper->getCompany($customer);
        if ($company) {
            if ($company->getSuperUserId() !== $customer->getId()) {
                // not syncing, but say it was successful anyway to remove the sync entry
                $this->logger->debug('Skipping company update for ' . $customer->getEmail() . ' num: ' . $customerNum . ' because they are not company admin');
                return true;
            }
        }

        $customerId = $customer->getId();
        if (!$customerNum) {
            $this->logger->debug(
                "Attempting to update customer $customerId that does not have a sales pad customer number"
            );
        }
        $customerData = $this->_convert($customer);

        if (!$this->config->getCustomerAllowNameClassChange()) {
            unset($customerData['Customer_Name']);
            unset($customerData['Customer_Class']);
        }
        // CLMI-1007 - Zephan Hazell asked to remove customer discount from this on updates
        // UserFieldData/Names for Customers only has the xCustomerDiscount field, so safe to unset
        // both.  If we end up adding more, this may need to be revisted.
        unset($customerData['UserFieldData']);
        unset($customerData['UserFieldNames']);
        unset($customerData['Customer_Name']);
        unset($customerData['Customer_Class']);
        unset($customerData['Payment_Terms']);


        $action = sprintf(self::ACTION_UPDATE, strval($customerNum));
        try {
            $response = $this->api->callApi($action, Api::PUT, $customerData);
            if (!$response) {
                $this->logger->debug("Call to $action failed to return response object");
                return false;
            }

            $responseCode = $response->getStatusCode();
            if ($responseCode == 200 || $responseCode == 201) {
                $data = $this->api->extractJson($response);
                $respCustomerNum = $data[self::CUSTOMER_NUM] ?? false;
                $this->logger->debug(
                    "Customer " . $customer->getId() . " updated successfully ($customerNum)"
                );
                if ($respCustomerNum != $customerNum) {
                    $this->logger->debug(
                        "SalesPad customer number for $customerId changed from $customerNum to $respCustomerNum."
                    );
                    return $this->customerHelper->saveSalesPadCustomerNumber($customer, $respCustomerNum);
                }
                return $customerNum;
            } else {
                $this->logger->debug(
                    "API call $action returned status code $responseCode"
                );
                $this->_addFailure($response, $responseCode);
            }
        } catch (\Exception $e) {
            $this->logger->debug("Exception updating customer " . $customer->getId() . ": " . strval($e));
            $this->_addFailureException($e);
            return false;
        }
        return false;
    }

    /**
     * @param CustomerInterface $customer
     * @return bool|string
     */
    public function create(CustomerInterface $customer)
    {
        $this->clearFailures();
        $id = $customer->getId();
        $email = $customer->getEmail();
        $website = $customer->getWebsiteId();
        $customerNum = $this->customerNumResolver->execute($id, $email, $website);
        if (!$customerNum) {
            $customerData = $this->_convert($customer);
            $customerNum = $this->_doCreate($customerData, $id);
        }
        return $this->customerHelper->saveSalesPadCustomerNumber($customer, $customerNum);
    }

    /**
     * @param $customerData
     * @param string|int $customerId
     * @return bool|mixed
     */
    protected function _doCreate($customerData, $customerId = 'guest')
    {
        $customerId = strval($customerId);
        try {
            $response = $this->api->callApi(self::ACTION_CREATE, Api::POST, $customerData);
            if (!$response) {
                $this->logger->debug("Call to " . self::ACTION_CREATE . " failed to return response object");
                return false;
            }
            $responseCode = $response->getStatusCode();
            if ($responseCode == 200 || $responseCode == 201) {
                $data = $this->api->extractJson($response);
                $customerNum = $data[self::CUSTOMER_NUM] ?? false;
                $this->logger->debug(
                    "Customer $customerId created successfully ($customerNum)"
                );
                return $customerNum;
            } else {
                $this->logger->debug(
                    "API call " . self::ACTION_CREATE . " returned status code $responseCode"
                );
                $this->_addFailure($response, $responseCode);
            }
        } catch (\Exception $e) {
            $this->logger->debug("Exception creating customer $customerId: " . strval($e));
            $this->_addFailureException($e);
            return false;
        }
        return false;
    }

    /**
     * @param CustomerInterface $customer
     * @return array
     */
    protected function _convert(CustomerInterface $customer)
    {
        $customerName = $customer->getFirstname() . ' ' . $customer->getLastname();
        try {
            $companyNameAttribute = $customer->getCustomAttribute('customer_company_name');
            if (!empty($companyNameAttribute)) {
                $companyName = $companyNameAttribute->getValue();
            }
            $customerName = (!empty($companyName)) ? $companyName : $customerName;
        } catch (\Exception $e) {
            $this->logger->debug('Getting Customer company name was not success');
        }
        $company = $this->customerHelper->getCompany($customer);
        $discount = '';
        $class = 'RETAIL';
        if ($company) {
            $customerName = $company->getCompanyName();
            $class = $this->_getCustomerClass($company->getBusinessType());
        }

        $customerData = [
            'Customer_Name'             => $customerName,
            'Primary_Addr_Code'         => 'PRIMARY',
            'Primary_Bill_To_Addr_Code' => 'PRIMARY',
            'Primary_Ship_To_Addr_Code' => 'PRIMARY',
            'Customer_Class'            => $class,
            'Price_Level'               => 'RETAIL',
            'Payment_Terms'             => 'PRE PAID',
            'Currency_ID'               => $this->config->getCurrencyId()
        ];

        return $customerData;
    }

    /**
     * @param CustomerInterface $customer
     * @param $addressId
     * @return bool|\Magento\Customer\Api\Data\AddressInterface|mixed
     */
    public function getCustomerAddressById(CustomerInterface $customer, $addressId)
    {
        try {
            $addresses = $customer->getAddresses();
            foreach ($addresses as $address) {
                if ($address->getId() == $addressId) {
                    return $address;
                }
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param CustomerInterface $customer
     * @return bool|mixed
     */
    public function createOrUpdate(CustomerInterface $customer)
    {
        $this->clearFailures();
        // CRC-638 Moved sales_pad_customer_num customer attribute from a custom to an extension one.
        if ($customer->getExtensionAttributes()->getSalesPadCustomerNum() !== null &&
            $customer->getExtensionAttributes()->getSalesPadCustomerNum() !== false) {
            return $this->update($customer);
        } else {
            return $this->create($customer);
        }
    }

    /**
     * @param string $name
     * @return bool|string
     */
    public function createGuestCustomer($name, $email)
    {
        $customerData = [
            'Customer_Name' => $name,
            'Primary_Addr_Code'         => 'PRIMARY',
            'Primary_Bill_To_Addr_Code' => 'PRIMARY',
            'Primary_Ship_To_Addr_Code' => 'PRIMARY',
            'Customer_Class'            => 'RETAIL',
            'Price_Level'               => 'RETAIL',
            'Payment_Terms'             => 'PRE PAID',
            'Currency_ID'               => $this->config->getCurrencyId()
        ];
        return $this->_doCreate($customerData);
    }

    protected function _getCustomerClass($businessType)
    {
        $businessType = strtoupper(trim($businessType));
        $businessTypes = $this->businessTypeSource->toArray();
        $businessTypes = array_map(function($s) {
            return strtoupper(trim($s));
        }, $businessTypes);
        if (in_array($businessType, $businessTypes)) {
            return $businessType;
        } else {
            return 'OTHER';
        }
    }

    /**
     * @return array
     */
    public function getFailures()
    {
        return $this->failures;
    }

    public function clearFailures()
    {
        $this->failures = [];
    }

    protected function _addFailureException(\Exception $e)
    {
        if (count($this->failures) > self::FAILURE_MAX) {
            $this->logger->debug('Too many failures: ' . count($this->failures) . ', cleaning up:');
            $this->logger->debug($this->failures);
            // make sure to note that the failures were automatically cleared
            $this->failures = ['Check application log files for more information'];
            // but for exceptions, make sure the message/trace is included
        }
        $this->failures[] = $e->getMessage() . ": trace:\n" . $e->getTraceAsString();
    }

    protected function _addFailure($response, $responseCode)
    {
        $this->failures[] = $responseCode . ': ' . $response->getRawBody();
        // prevent failure data from stacking up too much if there is a long running process that is doing lots of api calls
        if (count($this->failures) > self::FAILURE_MAX) {
            $this->logger->debug('Too many failures: ' . count($this->failures) . ', cleaning up:');
            $this->logger->debug($this->failures);
            // make sure to note that the failures were automatically cleared
            $this->failures = ['Check application log files for more information'];
        }
    }
}
