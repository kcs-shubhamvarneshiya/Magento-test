<?php
/**
 * Capgemini_OrderSplit
 * php version 7.4.27
 *
 * @category  Capgemini
 * @package   Capgemini_OrderSplit
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link      https://www.capgemini.com
 */

declare(strict_types=1);

namespace Capgemini\OrderSplit\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * class Data
 */
class Data extends AbstractHelper
{
    /**
     * @var Json
     */
    protected Json $serializer;

    /**
     * Data constructor
     *
     * @param Context $context
     * @param Json $serializer
     */
    public function __construct(
        Context $context,
        Json $serializer
    ) {
        parent::__construct($context);
        $this->serializer = $serializer;
    }

    /**
     * @param $string
     *
     * @param string|null $attribute
     *
     * @return array
     */
    public function getOrderCustomAttributeAsArray($string, $attribute = null): array
    {
        $data = [];
        if (is_string($string)) {
            $data = $this->serializer->unserialize($string) ?? [];
            if ($attribute) {
                $data = array_column($data, $attribute);
            }
            return $data;
        }

        return $data;
    }
}
