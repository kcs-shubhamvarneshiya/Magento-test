<?php
/**
 * Capgemini_ShipOnDate
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\ShipOnDate\Plugin\Sales\Model;

use Capgemini\ShipOnDate\Helper\Date;
use Magento\Sales\Model\Order;

class OrderPlugin
{
    /**
     * Date Formatter
     * @var Date
     */
    protected Date $dateHelper;

    /**
     * Constructor
     *
     * @param Date $dateHelper
     */
    public function __construct(
        Date $dateHelper
    ) {
        $this->dateHelper = $dateHelper;
    }

    /**
     * @param Order $subject
     * @param $result
     * @return mixed|string
     */
    public function afterGetShippingDescription(Order $subject, $result)
    {
        $shippingMethod = $subject->getShippingMethod();
        if ($shippingMethod !== null) {
            if (str_contains($shippingMethod, 'ship_on_date')) {
                return $this->dateHelper->formatShippingDescription($result);
            }
        }

        return $result;
    }
}
