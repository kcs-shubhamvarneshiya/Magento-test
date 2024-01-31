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

namespace Capgemini\WholesalePrice\Pricing;

use Capgemini\WholesalePrice\Helper\Customer;
use Capgemini\WholesalePrice\Model\Price as ApiPriceModel;
use Magento\Framework\Pricing\Render\Layout;
use Magento\Framework\Pricing\SaleableInterface;
use Magento\Framework\View\Element\Template\Context;

/**
 * Render class
 */
class Render extends \Magento\Framework\Pricing\Render
{
    /**
     * @var Customer
     */
    protected Customer $customerHelper;

    /**
     * @var ApiPriceModel
     */
    protected ApiPriceModel $priceModel;

    /**
     * @param Customer $customerHelper
     * @param ApiPriceModel $priceModel
     * @param Context $context
     * @param Layout $priceLayout
     * @param array $data
     */
    public function __construct(
        Customer $customerHelper,
        ApiPriceModel $priceModel,
        Context $context,
        \Magento\Framework\Pricing\Render\Layout $priceLayout,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $priceLayout,
            $data
        );
        $this->customerHelper = $customerHelper;
        $this->priceModel = $priceModel;
    }

    /**
     * Render price
     *
     * @param string $priceCode
     * @param SaleableInterface $saleableItem
     * @param array $arguments
     * @return string
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function render($priceCode, SaleableInterface $saleableItem, array $arguments = [])
    {
        /** @var \Magento\Framework\Pricing\Render\RendererPool $rendererPool */
        $rendererPool = $this->priceLayout->getBlock('render.product.prices');
        if ($this->priceModel->canUseAdvancedPricing(
            $saleableItem
        )) {
            $rendererPool = $this->priceLayout->getBlock($this->getPriceBlockName());
        }

        $useArguments = array_replace($this->_data, $arguments);

        if (!$rendererPool) {
            throw new \RuntimeException('Wrong Price Rendering layout configuration. Factory block is missed');
        }

        // obtain concrete Price Render
        $priceRender = $rendererPool->createPriceRender($priceCode, $saleableItem, $useArguments);
        return $priceRender->toHtml();
    }

    /**
     * @return string
     */
    protected function getPriceBlockName(): string
    {
        return 'render.product.price.render.ajax';
    }
}
