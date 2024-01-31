<?php

namespace Lyonscg\SalesPad\Model;

use Lyonscg\SalesPad\Api\CustomerLinkRepositoryInterface;
use Lyonscg\SalesPad\Model\Api as Api;
use Lyonscg\SalesPad\Model\Api\Customer;

class CustomerNumResolver
{
    /**
     * @var CustomerLinkRepositoryInterface
     */
    protected $customerLinkRepository;

    /**
     * @var \Lyonscg\SalesPad\Model\Api
     */
    protected $api;

    /**
     * @param CustomerLinkRepositoryInterface $customerLinkRepository
     * @param \Lyonscg\SalesPad\Model\Api $api
     */
    public function __construct(
        CustomerLinkRepositoryInterface $customerLinkRepository,
        Api $api
    ) {
        $this->customerLinkRepository = $customerLinkRepository;
        $this->api = $api;
    }

    /**
     * @param $id
     * @param $email
     * @param $website
     * @return false|int|string
     */
    public function execute($id, $email, $website)
    {
        // Do we have a customer number associated with this email?
        $exists = $this->customerLinkRepository->get($id ?: true, $email, $website);
        if ($exists === false) {
            $exists = $this->customerLinkRepository->get(false, $email, $website);
        }
        if ($exists !== false) {
            return $exists;
        }

        // Is there a unique customer number associated to this email address?

        $spAddresses = $this->getByEmail($email);
        if ($spAddresses !== false && count($spAddresses['Items']) > 0) {
            $foundNumbers = [];
            foreach ($spAddresses['Items'] as $spAddress) {
                $customerNum = trim($spAddress['Customer_Num']);
                if ($customerNum) {
                    $foundNumbers[] = $customerNum;
                }
            }
            $foundNumbers = array_unique($foundNumbers);
            $foundNumbers = $this->customerLinkRepository->getSubsetOfCustomerNumsForWebsite($website, $foundNumbers);
            if (count($foundNumbers) === 1) {
                $this->customerLinkRepository->add(
                    $id,
                    $email,
                    $website,
                    trim($foundNumbers[0])
                );
                return $foundNumbers[0];
            }
        }
        // Either no unique customer number, or the customer number for this email does
        // not exist.  In any case, if necessary we will need to call createCustomer
        // to create the customer in SalesPad.
        return false;
    }

    /**
     * @param $email
     * @return array|bool|float|int|mixed|string|null
     */
    protected function getByEmail($email)
    {
        $filterParts[] = sprintf("%s eq '%s'", 'Email', $email);
        return $this->api->searchByFilters($filterParts, Customer::ACTION_SEARCH);
    }
}
