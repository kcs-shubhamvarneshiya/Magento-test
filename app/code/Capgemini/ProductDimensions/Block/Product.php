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

namespace Capgemini\ProductDimensions\Block;

use Capgemini\ProductDimensions\Model\DimensionsConfig;
use Magento\Catalog\Block\Product\View\Description;
use Magento\Framework\Registry;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\Template\Context;

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
class Product extends Description
{
    /**
     * Short description
     *
     * @var string
     */
    protected $_template = 'Capgemini_ProductDimensions::product/view/specs-wrapper.phtml';

    /**
     * @var Json
     */
    protected Json $jsonEncoder;
    protected $_productCollectionFactory;

    /**
     * @var DimensionsConfig
     */
    protected DimensionsConfig $dimensionsConfig;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param DimensionsConfig $dimensionsConfig
     * @param Json $jsonEncoder
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        DimensionsConfig $dimensionsConfig,
        Json $jsonEncoder,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $_productCollectionFactory,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $data
        );
        $this->jsonEncoder = $jsonEncoder;
        $this->dimensionsConfig = $dimensionsConfig;
        $this->_productCollectionFactory = $_productCollectionFactory;
    }

    /**
     * @param $product
     *
     * @return bool|string
     */
    public function getJsonConfig($product)
    {
        return $this->jsonEncoder->serialize(
            $this->dimensionsConfig->getConfig($product)
        );
    }

    public function getRelativeCount(){
        
        $productCollection = $this->_productCollectionFactory->create();
        $_itemCollection = $productCollection 
                    ->addAttributeToSelect('relatives')
                    ->addFieldToFilter('relatives', ['eq' => $this->getProduct()->getRelatives()])
                    ->addFieldToFilter('sku', ['neq' => $this->getProduct()->getSku()])
                    ->addStoreFilter()
                    ->setVisibility([2,4])
                    ->setPageSize(1)
                    ->load();

        
        return $_itemCollection->count();
    }
}
