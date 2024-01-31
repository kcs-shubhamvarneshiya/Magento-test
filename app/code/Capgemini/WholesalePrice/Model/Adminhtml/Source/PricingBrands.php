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

namespace Capgemini\WholesalePrice\Model\Adminhtml\Source;

use Magento\Catalog\Api\ProductAttributeRepositoryInterface;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class PricingBrands implements OptionSourceInterface
{
    public const REPORTING_BRAND_ATTRIBUTE = 'reporting_brand';

    /**
     * @var ProductAttributeRepositoryInterface
     */
    protected ProductAttributeRepositoryInterface $attributeRepository;

    /**
     * @param ProductAttributeRepositoryInterface $attributeRepository
     */
    public function __construct(
        ProductAttributeRepositoryInterface $attributeRepository
    ) {
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * @return array
     */
    public function toOptionArray(): array
    {
        try {
            $attribute = $this->attributeRepository->get(self::REPORTING_BRAND_ATTRIBUTE);
            return $attribute->usesSource()
                ? $attribute->getSource()->getAllOptions()
                : [];
        } catch (NoSuchEntityException $e) {
        }

        return [];
    }
}
