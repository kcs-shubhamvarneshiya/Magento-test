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

namespace Capgemini\Dimensions\Model;

use Capgemini\Dimensions\Helper\Data;

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
class Converter
{
    public const INCH_SYMBOL = '"';

    /**
     * Short description
     *
     * @var array
     */
    protected array $converterConfig = [
        '/([\d.]+)("|in)/i' => ['cm', 2.54],
        "/([\d.]+)('|ft)/i" => ['m', .3048]
    ];

    /**
     * Short description
     *
     * @var Data
     */
    protected Data $helper;

    /**
     * Short description
     *
     * @param Data $helper Param description
     */
    public function __construct(
        Data $helper
    ) {
        $this->helper = $helper;
    }

    /**
     * Short description
     *
     * @param string $value description
     *
     * @return string
     */
    public function convert(string $value): string
    {
        $convertedValue = $value;
        foreach ($this->converterConfig as $pattern => $config) {
            list($unitDimension, $unitConvertValue) = $config;
            $convertedValue = preg_replace_callback(
                $pattern,
                function ($matches) use ($unitDimension, $unitConvertValue) {
                    $value = $matches[1];
                    $value = $this->helper->formatPrice(
                        (float)$value * $unitConvertValue
                    );
                    return $value . $unitDimension;
                },
                $convertedValue
            );
        }
        return $convertedValue;
    }
}
