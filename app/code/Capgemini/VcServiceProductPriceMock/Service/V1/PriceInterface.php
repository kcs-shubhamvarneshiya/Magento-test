<?php
/**
 * Capgemini_VcServiceProductPriceMock
 * php version 7.4.27
 *
 * @category  Capgemini
 * @package   Capgemini_VcServiceProductPriceMock
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link      https://www.capgemini.com
 */

declare(strict_types=1);

namespace Capgemini\VcServiceProductPriceMock\Service\V1;

use Capgemini\VcServiceProductPriceMock\Api\ResponseDataInterface;

interface PriceInterface
{
    /**
     * @param mixed $products
     * @param string $currency
     * @param mixed|null $accounts
     * @return ResponseDataInterface
     */
    public function getData($products, $currency, $accounts = null): ResponseDataInterface;
}
