<?php
/**
 * Capgemini_ProductDimensions
 * php version 7.4.27
 *
 * @category  Capgemini
 * @package   Capgemini_ProductDimensions
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link      https://www.capgemini.com
 */
declare(strict_types=1);

namespace Capgemini\ProductDimensions\Controller\Ajax;

use Capgemini\ProductDimensions\Block\Dimensions as DimensionsBlock;
use Magento\Framework\Controller\Result\Raw;

/**
 * Short description
 *
 * @category  Capgemini
 * @package   Capgemini_ProductDimensions
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link      https://www.capgemini.com
 */
class Dimensions extends Block
{
    /**
     * Short description
     *
     * @return Raw
     */
    public function execute()
    {
        $productId = $this->request->getParam('product_id');
        $rawResult = $this->rawFactory->create();

        try {
            $product = $this->productRepository->getById($productId);
            $resultPage = $this->resultPageFactory->create();

            /** @var DimensionsBlock $block */
            $block = $resultPage->getLayout()->createBlock(
                DimensionsBlock::class
            );

            $block->setProduct($product)
                ->setBlockTitle("Dimensions")
                ->setAttributeCodePrefix("dimension_")
                ->setValueTitle("IN")
                ->setMetricTItle("CM")
                ->setDisplayMetric(true);

            $rawResult->setContents($block->toHtml());

            return $rawResult;
        } catch (\Exception $exception) {
            return $rawResult;
        }
    }
}
