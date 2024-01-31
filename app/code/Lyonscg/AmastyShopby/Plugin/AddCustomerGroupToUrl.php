<?php

namespace Lyonscg\AmastyShopby\Plugin;

use Amasty\Shopby\Model\Layer\Filter\Item;
use Exception;
use Magento\Customer\Model\Session;

class AddCustomerGroupToUrl
{
    private Session $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function afterGetUrl(Item $subject, $result)
    {
        try {
            if ($groupId = $this->session->getCustomerGroupId()) {
                $search = 'c_group=' . $groupId;

                if (str_contains($result, $search)) {
                    $result = str_replace($search, '', $result);
                }

                $result = str_replace('?', '?' . $search . '&', $result);
            }
        } catch (Exception $exception) {

        }

        return $result;
    }
}
