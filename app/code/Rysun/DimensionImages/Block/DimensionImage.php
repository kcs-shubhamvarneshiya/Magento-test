<?php 
declare(strict_types=1);

namespace Rysun\DimensionImages\Block;


use Magento\Framework\View\Element\Template\Context;
use Rysun\DimensionImages\Model\DimensionImages;
use Magento\Framework\Registry;


class DimensionImage extends \Magento\Framework\View\Element\Template 
{

    protected $registry;
    protected $dimensionImages;

    const IMAGE_TYPE = 'Dimension';

    public function __construct(
        Context $context,
        Registry $registry,
        DimensionImages $dimensionImages
    )
    {
     
        $this->registry = $registry;
        $this->dimensionImages = $dimensionImages;
        parent::__construct($context);
    }

    /**
     * return Current product
     */
    public function getCurrentProduct()
    {
            return $this->registry->registry('current_product');
    }

    /**
     * Retrieve document product ids from custom table
     * based on given product id
     */
    public function getDimensionImages(){

        $product = $this->getCurrentProduct();
        $productId = $product->getSqlServId();

        $collection = $this->dimensionImages->getCollection(); //->load();
        $collection->addFieldToFilter('sql_serv_prod_id', $productId);
        $collection->addFieldToFilter('image_type', self::IMAGE_TYPE);

       //echo '<pre> count images ';
       $imageList = [];
        foreach($collection as $item){

            $image = [];
            $image['url'] = $item['image_url'].$item['image_file_name'];
            $image['alt_tag'] = $item['image_alt_tag'];
            $image['name'] = $item['image_file_name'];
            $imageList[] = $image;
            //print_r($item->debug());
        }

        return $imageList;
        //exit;

    }

}

