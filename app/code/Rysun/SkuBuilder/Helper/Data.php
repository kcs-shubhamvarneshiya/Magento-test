<?php
namespace Rysun\SkuBuilder\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;
use Rysun\DataTransfer\Model\ProductTrimFactory;

class Data extends AbstractHelper{

    protected $productTrimModel;

    public function __contruct(
        ProductTrimFactory $productTrimModel
    )
    {
       $this->productTrimModel = $productTrimModel;
    }



    /**
     * Retrieve trim product ids from custom table
     * based on given product id
     */
    public function getTrimProductIds($productId){

        $collection = $this->productTrimModel->create();
        $collection->addFieldToFilter('product_id',$productId);
        //$collection->addAttributeToSelect('sku');
        //$collection->addAttributeToFilter('sql_serv_id', ['eq' => $sqlServerId]);
        $trimProductId = [];
        if(count($collection) > 0){
            foreach($collection as $product){
                $trimProductId = $product->getId();
            }
        }

        return null;
    }

}