<?php

namespace Lyonscg\SalesPad\Plugin;

use Lyonscg\SalesPad\Api\CustomerLinkRepositoryInterface;
use Magento\Customer\Model\Customer\DataProviderWithDefaultAddresses;

class DataProviderWithDefaultAddressesPlugin
{

    /**
     * @var CustomerLinkRepositoryInterface
     */
    private $customerLinkRepository;

    public function __construct(CustomerLinkRepositoryInterface $customerLinkRepository)
    {
        $this->customerLinkRepository = $customerLinkRepository;
    }

    /**
     * Add assistance_allowed extension attribute data to Customer form Data Provider.
     *
     * @param DataProviderWithDefaultAddresses $subject
     * @param array $result
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetData(
        DataProviderWithDefaultAddresses $subject,
        array $result
    ): array {
        $toMerge = [];

        foreach ($result as $id => $entityData) {
            if ($id) {
                $customerNum = $this->customerLinkRepository->get($id, null, null);
                $toMerge[$id]['customer']['extension_attributes']['sales_pad_customer_num'] =
                    (string)$customerNum;
            }
        }

        return array_replace_recursive($result, $toMerge);
    }

    /**
     * Modify assistance_allowed extension attribute metadata for Customer form Data Provider.
     *
     * @param DataProviderWithDefaultAddresses $subject
     * @param array $result
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetMeta(
        DataProviderWithDefaultAddresses $subject,
        array $result
    ): array {

        $config = [
            'customer' => [
                'children' => [
                    'extension_attributes.sales_pad_customer_num' => [
                        'arguments' => [
                            'data' => []
                        ],
                    ],
                ],
            ],
        ];

        return array_replace_recursive($result, $config);
    }
}
