<?php
/**
 * Capgemini_WholesalePrice
 * php version 7.4.27
 *
 * @category  Capgemini
 * @package   Capgemini_WholesalePrice
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link      https://www.capgemini.com
 */

declare(strict_types=1);

namespace Capgemini\WholesalePrice\Plugin\Block\Product;

use Capgemini\WholesalePrice\Model\Price;
use Magento\Catalog\Block\Product\View;
use Magento\Framework\Serialize\Serializer\Json;

class ViewPlugin
{
    /**
     * @var Json
     */
    protected Json $jsonEncoder;

    /**
     * @var Price
     */
    protected Price $advancedPrice;

    /**
     * Constructor
     *
     * @param Json $jsonEncoder
     * @param Price $advancedPrice
     */
    public function __construct(
        Json $jsonEncoder,
        Price $advancedPrice
    ) {
        $this->jsonEncoder = $jsonEncoder;
        $this->advancedPrice = $advancedPrice;
    }

    /**
     * @param View $subject
     * @param string $result
     *
     * @return string
     */
    public function afterGetJsonConfig(View $subject, string $result): string
    {
        $configEncoded = $this->jsonEncoder->unserialize($result);
        $product = $subject->getProduct();
        $config = [
            $product->getId() => $this->advancedPrice->canUseAdvancedPricing($product)
        ];
        $configEncoded['advancedPriceConfig'] = $config;
        return $this->jsonEncoder->serialize($configEncoded);
    }
}
