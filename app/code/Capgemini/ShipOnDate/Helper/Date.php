<?php
/**
 * Capgemini_ShipOnDate
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\ShipOnDate\Helper;

use Exception;
use Magento\Framework\App\Helper\AbstractHelper;

class Date extends AbstractHelper
{
    /**
     * @throws Exception
     */
    public function formatDate($dateString): string
    {
        $date = new \DateTime($dateString);
        return $date->format('m/d/Y');
    }

    /**
     * @param $shippingDescription
     * @return string
     */
    public function formatShippingDescription($shippingDescription): string
    {
        $exploded = explode('-', $shippingDescription);
        return $exploded[0] ?? $shippingDescription;
    }
}
