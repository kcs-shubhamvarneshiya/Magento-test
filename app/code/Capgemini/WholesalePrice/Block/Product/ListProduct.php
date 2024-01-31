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

namespace Capgemini\WholesalePrice\Block\Product;

use Capgemini\WholesalePrice\Helper\Customer;
use Capgemini\WholesalePrice\Helper\Data as AdvancedPricingHelper;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Helper\Output as OutputHelper;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Framework\Data\Helper\PostHelper;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Pricing\Render;
use Magento\Framework\Url\Helper\Data as UrlHelper;

/**
 * ListProduct class
 */
class ListProduct extends \Magento\Catalog\Block\Product\ListProduct
{
    /**
     * @var Customer
     */
    protected Customer $customerHelper;

    /**
     * @var AdvancedPricingHelper
     */
    protected AdvancedPricingHelper $advancedPricingHelper;

    public $frameworkRegistry;

    /**
     * Constructor
     *
     * @param AdvancedPricingHelper $advancedPricingHelper
     * @param Customer $customerHelper
     * @param Context $context
     * @param PostHelper $postDataHelper
     * @param Resolver $layerResolver
     * @param CategoryRepositoryInterface $categoryRepository
     * @param UrlHelper $urlHelper
     * @param array $data
     * @param OutputHelper|null $outputHelper
     */
    public function __construct(
        AdvancedPricingHelper $advancedPricingHelper,
        Customer $customerHelper,
        Context                     $context,
        PostHelper                  $postDataHelper,
        Resolver                    $layerResolver,
        CategoryRepositoryInterface $categoryRepository,
        UrlHelper $urlHelper,
        \Magento\Framework\Registry $frameworkRegistry,
        array $data = [],
        ?OutputHelper $outputHelper = null
    ) {
        parent::__construct(
            $context,
            $postDataHelper,
            $layerResolver,
            $categoryRepository,
            $urlHelper,
            $data,
            $outputHelper
        );
        $this->advancedPricingHelper = $advancedPricingHelper;
        $this->customerHelper = $customerHelper;
        $this->frameworkRegistry = $frameworkRegistry;
    }

    /**
     * Specifies that price rendering should be done for the list of products.
     * (rendering happens in the scope of product list, but not single product)
     *
     * @return Render
     *
     * @throws LocalizedException
     */
    protected function getPriceRender(): Render
    {
        if ($this->advancedPricingHelper->isEnabled()
         && $this->customerHelper->isCustomerWholesale()) {
            return $this->getLayout()->getBlock('product.price.render.ajax')
                ->setData('is_product_list', true);
        }

        return parent::getPriceRender();
    }
}
