<?php
/**
 * Capgemini_Dimensions
 * php version 7.4.27
 *
 * @category  Capgemini
 * @package   Capgemini_Dimensions
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link      https://www.capgemini.com
 */
declare(strict_types=1);

namespace Capgemini\Dimensions\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

/**
 * Short description
 *
 * @category  Capgemini
 * @package   Capgemini_Dimensions
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link      https://www.capgemini.com
 */
class Data extends AbstractHelper
{
    /**
     * Short description
     *
     * @param float $val description
     *
     * @return float
     */
    public function formatPrice(float $val): float
    {
        return (float) number_format($val, 2, '.', '');
    }
}
