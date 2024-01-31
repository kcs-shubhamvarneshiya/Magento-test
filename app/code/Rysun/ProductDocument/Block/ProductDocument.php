<?php 
declare(strict_types=1);

namespace Rysun\ProductDocument\Block;

use Magento\Framework\View\Element\Template\Context;
use Rysun\ProductDocument\Helper\Data;
use Rysun\ProductDocument\Model\ProductDocument as DocumentFactory;
use Rysun\ProductDocument\Model\ProductDocumentLink;
use Magento\Framework\Registry;




class ProductDocument extends \Magento\Framework\View\Element\Template{


    protected $sample;
    protected $registry;
    protected $productDocument;
    protected $productDocumentLink;

    public function __construct(
        Context $context,
        DocumentFactory $productDocument,
        ProductDocumentLink $productDocumentLink,
        Registry $registry
    )
    {

        $this->productDocument = $productDocument;
        $this->registry = $registry;
        $this->productDocumentLink = $productDocumentLink;
        parent::__construct($context);
    }
    
    public function getOutput(){
        $this->sample = 'Dummy data';

        return $this->sample;
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
    public function getProductDocument(){
       
        $product = $this->getCurrentProduct();
        $productId = $product->getId();
        $collection = $this->productDocumentLink->getCollection(); //->load();
        $model = $this->productDocument;

        $collection->addFieldToFilter('sql_serv_prod_id', $productId);
        
        $documentList = [];
        $documentId = [];
        foreach($collection as $item){
            $documentId[] = $item->getDocumentId();
        }
        
        if(empty($documentId)){
            return $documentList;
        }
        $documentCollection = $this->productDocument->getCollection(); //->load();
        $documentCollection->addFieldToFilter('productdocument_id', ["in" => $documentId]);

        
        foreach($documentCollection as $documentItem){
            $documentData = [];
            $documentData['filename']= $documentItem->getDocumentFileName();
            $documentData['url']= $documentItem->getDocumentUrl();
            //print_r($documentItem->getDocumentUrl());
            $documentList[] = $documentData;
        }

        return $documentList;

    }

} 