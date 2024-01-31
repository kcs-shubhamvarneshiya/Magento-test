<?php

namespace Lyonscg\SalesPad\Model\Api;

use Lyonscg\SalesPad\Helper\Order as OrderHelper;
use Lyonscg\SalesPad\Model\Api;
use Magento\Customer\Api\Data\AddressInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\Customer as CustomerModel;
use Magento\Sales\Api\Data\OrderAddressInterface;
use Magento\Sales\Api\Data\OrderInterface;

class CustomerAddr
{
    const ACTION_GET = 'api/CustomerAddr/%s/%s';
    const ACTION_SEARCH = 'api/CustomerAddr%s';
    const ACTION_CREATE = 'api/CustomerAddr';
    const ACTION_UPDATE = 'api/CustomerAddr/%s/%s';
    const ACTION_ADDRESS_CODE = 'api/CustomerAddr/%s/AddressCode';

    /**
     * Fields to filter addresses when searching for an existing address before creating it
     * @var string[]
     */
    protected $filterableFields = [
        'Customer_Num',
        'Email',
        'Contact_Person',
        'City',
        'State',
        'Zip',
        'Phone_1',
        'Address_Line_1',
        'Address_Code'
    ];

    /**
     * @var Api
     */
    protected $api;

    /**
     * @var Logger
     */
    protected $logger;

    protected $orderHelper;

    /**
     * CustomerAddr constructor.
     * @param Api $api
     * @param Logger $logger
     */
    public function __construct(
        Api $api,
        \Lyonscg\SalesPad\Model\Api\Logger $logger,
        OrderHelper $orderHelper
    ) {
        $this->api = $api;
        $this->logger = $logger;
        $this->orderHelper = $orderHelper;
    }

    /**
     * @param $customerNum
     * @param $addressCode
     * @return bool|mixed
     */
    public function get($customerNum, $addressCode)
    {
        $customerNum = trim($customerNum);
        $addressCode = trim($addressCode);
        try {
            if (preg_match('/\s/', $addressCode)) {
                $result =  $this->_search([
                    'Customer_Num' => $customerNum,
                    'Address_Code' => $addressCode
                ]);

                return $result['Items'][0] ?? false;
            }

            $action = sprintf(self::ACTION_GET, strval($customerNum), strval($addressCode));
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
     * @param CustomerModel $customer
     * @param AddressInterface $address
     * @param $customerNum
     * @param $addressCode
     * @return bool
     */
    public function create(CustomerModel $customer, AddressInterface $address, $customerNum, $addressCode = null)
    {
        $addressData = $this->_convertCustomerAddress($customer, $address, $customerNum, $addressCode);
        return $this->_doCreate($addressData, $address->getId());
    }

    public function createFromOrderAddress(OrderInterface $order, OrderAddressInterface $address, $customerNum, $addressCode = null)
    {
        $addressData = $this->_convertOrderAddress($order, $address, $customerNum, $addressCode);
        return $this->_doCreate($addressData, $address->getId());
    }

    protected function _doCreate($addressData, $addressId)
    {
        try {
            $existingAddressCode = $this->_checkIfAddressExists($addressData);
            if ($existingAddressCode !== false) {
                $this->logger->debug("Address $addressId exists in SalesPad: Address_Code: $existingAddressCode,  data " . print_r($addressData, true));
                return $existingAddressCode;
            }

            if (!$addressData['Address_Code']) {
                $addressData['Address_Code'] = $this->getNextAddressCodeForCustomerNum($addressData['Customer_Num']);
            }

            $response = $this->api->callApi(self::ACTION_CREATE, Api::POST, $addressData);
            if (!$response) {
                $this->logger->debug("Call to " . self::ACTION_CREATE . " failed to return response object");
                return false;
            }

            $responseCode = $response->getStatusCode();
            if ($responseCode == 200 || $responseCode == 201) {
                $data = $this->api->extractJson($response);
                $customerNum = $data['Customer_Num'] ?? false;
                $addressCode = $data['Address_Code'] ?? false;
                if ($customerNum === false || $addressCode === false) {
                    throw new \Exception('Address creation did not return Customer_Num and/or Address_Code');
                }
                $this->logger->debug(
                    "Address $addressId created successfully ($customerNum : $addressCode)"
                );
                return $addressCode;
            } else {
                $this->logger->debug(
                    "API call " . self::ACTION_CREATE . " returned status code $responseCode\n" .
                    $response->getContent()
                );
            }
        } catch (\Exception $e) {
            return false;
        }
        return false;
    }

    /**
     * @param CustomerModel $customer
     * @param AddressInterface $address
     * @param $customerNum
     * @param $addressCode
     * @return bool
     */
    public function update(CustomerModel $customer, AddressInterface $address, $customerNum, $addressCode)
    {
        // $action = sprintf(self::ACTION_UPDATE, strval($customerNum), strval($addressCode));
        // $addressData = $this->_convertCustomerAddress($customer, $address, $customerNum, $addressCode);
        // try {
        //     $response = $this->api->callApi($action, Api::PUT, $addressData);
        //     if (!$response) {
        //         $this->logger->debug("Call to $action failed to return response object");
        //         return false;
        //     }
        //
        //     $responseCode = $response->getStatusCode();
        //     if ($responseCode == 200 || $responseCode == 201) {
        //         $this->logger->debug(
        //             "Address " . $address->getId() . " updated successfully ($customerNum : $addressCode)"
        //         );
        //         return true;
        //     } else {
        //         $this->logger->debug(
        //             "API call $action returned status code $responseCode"
        //         );
        //     }
        // } catch (\Exception $e) {
        //     return false;
        // }
        return false;
    }

    /**
     * @param $customerNum
     * @return array|bool
     */
    public function getAddressCodes($customerNum)
    {
        $action = sprintf(self::ACTION_ADDRESS_CODE, strval($customerNum));
        try {
            $response = $this->api->callApi($action, Api::GET);
            if (!$response) {
                $this->logger->debug("Call to $action failed to return response object");
                return false;
            }

            $responseCode = $response->getStatusCode();
            if ($responseCode == 200 || $responseCode == 201) {
                $addressCodes = [];
                $data = $this->api->extractJson($response);
                if (!empty($data)) {
                    foreach ($data as $entry) {
                        $addressCode = $entry['AddressCode'] ?? false;
                        if ($addressCode !== false) {
                            $addressCodes[] = trim($addressCode);
                        }
                    }
                }
                return $this->orderHelper->sortAddressCodes($addressCodes, $customerNum);
            } else {
                $this->logger->debug(
                    "API call $action returned status code $responseCode"
                );
            }
        } catch (\Exception $e) {
            return [];
        }
        return [];
    }

    /**
     * @param CustomerModel $customer
     * @param AddressInterface $address
     * @param $customerNum
     * @param null $addressCode
     * @return array
     */
    protected function _convertCustomerAddress(CustomerModel $customer, AddressInterface $address, $customerNum, $addressCode = null)
    {
        $contactPerson = $customer->getFirstname() . ' ' . $customer->getLastname();

        $data = [
            'Customer_Num'      => $customerNum,
            'Email'             => $customer->getEmail(),
            'Contact_Person'    => $contactPerson,
            'Tax_Schedule'      => 'AVATAX',
            'Shipping_Method'   => 'BESTWAY',
            'Modified_On'       => date('c'),
            'Created_On'        => date('c'),
            'UserFieldData'     => [],
            'UserFieldNames'    => [],
            'Notifications'     => [],
            'Alt_Company_Name'  => $address->getCompany() ?: '',
            'City'              => $address->getCity(),
            'State'             => $address->getRegion()->getRegionCode(),
            'Zip'               => $address->getPostcode(),
            'Phone_1'           => $address->getTelephone(),
            'Fax'               => $address->getFax(),
            'Country_Code'      => $address->getCountryId(),
        ];

        $data['Address_Code'] = $addressCode;

        $streets = $address->getStreet();
        $count = min(3, count($streets));
        for ($i = 0; $i < $count; $i++) {
            $field = 'Address_Line_' . ($i + 1);
            $data[$field] = $streets[$i];
        }
        return $data;
    }

    /**
     * @param OrderInterface $order
     * @param OrderAddressInterface $address
     * @param $customerNum
     * @param null $addressCode
     * @return array
     */
    protected function _convertOrderAddress(OrderInterface $order, OrderAddressInterface $address, $customerNum, $addressCode = null)
    {
        $firstName = $address->getFirstname();
        $lastName = $address->getLastname();
        if (!$firstName) {
            $firstName = $order->getCustomerFirstname();
        }
        if (!$lastName) {
            $lastName = $order->getCustomerLastname();
        }
        $contactPerson = $firstName . ' ' . $lastName;
        $data = [
            'Customer_Num'      => $customerNum,
            'Email'             => $order->getCustomerEmail(),
            'Contact_Person'    => $contactPerson,
            'Tax_Schedule'      => 'AVATAX',
            'Shipping_Method'   => 'BESTWAY',
            'Modified_On'       => date('c'),
            'Created_On'        => date('c'),
            'UserFieldData'     => [],
            'UserFieldNames'    => [],
            'Notifications'     => [],
            'Alt_Company_Name'  => $address->getCompany() ?: '',
            'City'              => $address->getCity(),
            'State'             => $address->getRegionCode(),
            'Zip'               => $address->getPostcode(),
            'Phone_1'           => $address->getTelephone(),
            'Fax'               => $address->getFax(),
            'Country_Code'      => $address->getCountryId(),
        ];

        $data['Address_Code'] = $addressCode;

        $streets = $address->getStreet();
        $count = min(3, count($streets));
        for ($i = 0; $i < $count; $i++) {
            $field = 'Address_Line_' . ($i + 1);
            $data[$field] = $streets[$i];
        }
        return $data;
    }

    public function createBlankAddress($name, $email, $customerNum)
    {
        $addressCode = 'BLANKADDR';
        $data = [
            'Customer_Num'      => $customerNum,
            'Email'             => $email,
            'Contact_Person'    => $name,
            'Tax_Schedule'      => 'AVATAX',
            'Shipping_Method'   => 'BESTWAY',
            'Modified_On'       => date('c'),
            'Created_On'        => date('c'),
            'UserFieldData'     => [],
            'UserFieldNames'    => [],
            'Notifications'     => [],
            'Address_Code'      => $addressCode,
        ];

        return $this->_doCreate($data, 'blankaddr');
    }

    public function getNextAddressCodeForCustomerNum($customerNum)
    {
        $addressCodes = $this->getAddressCodes($customerNum);
        if (empty($addressCodes)) {
            return $this->orderHelper->getFirstAddressCode();
        } else {
            return $this->orderHelper->getNextAddressCode(end($addressCodes));
        }
    }

    protected function _checkIfAddressExists($addressData)
    {
        $results = $this->_search($addressData);
        if ($results === false) {
            return false;
        }

        $items = $results['Items'] ?? false;
        if (empty($items)) {
            return false;
        }

        foreach ($items as $item) {
            if (is_array($item)) {
                $addressCode = $item['Address_Code'] ?? false;
                if (!empty($addressCode)) {
                    // return first address that matches the data we have
                    return trim($addressCode);
                }
            }
        }
        return false;
    }

    /**
     * @param $addressData
     * @return array|bool|float|int|mixed|string|null
     */
    protected function _search($addressData)
    {
        $filterParts = [];
        foreach ($this->filterableFields as $field) {
            $value = $addressData[$field] ?? '';
            $value = trim($value);
            if (empty($value)) {
                continue;
            }
            // for ODATA search, replace single quotes with two to escape them
            $value = preg_replace("/'/", "''", $value);
            $filterParts[] = sprintf("%s eq '%s'", $field, $value);
        }
        return $this->api->searchByFilters($filterParts, self::ACTION_SEARCH);
    }

    /**
     * @param CustomerInterface $customer
     * @param AddressInterface $address
     * @param string $customerNum
     * @return false|string
     */
    public function checkAddress($customer, AddressInterface $address, string $customerNum)
    {
        $addressData = $this->_convertCustomerAddress($customer, $address, $customerNum);
        return $this->_checkIfAddressExists($addressData);
    }

    /**
     * @param $email
     * @return array|bool|float|int|mixed|string|null
     */
    public function findByEmail($email)
    {
        return $this->_search([
            'Email' => $email
        ]);
    }
}
