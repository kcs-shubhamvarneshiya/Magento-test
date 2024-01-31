<?php

namespace Rysun\ExcludeArch\Cron;

class ExcludeCron
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory
     */
    protected $categoryCollection;

    /**
     * @var \Magento\Catalog\Model\Category
     */
    protected $categoryModel;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $factoryCollection;

    /**
     * @var \Magento\Catalog\Model\Product
     */
    protected $productModel;

    /**
     * ExcludeCron constructor
     *
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollection
     * @param \Magento\Catalog\Model\Category $categoryModel
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $factoryCollection
     * @param \Magento\Catalog\Model\Product $productModel
     */
    public function __construct(
    \Magento\Store\Model\StoreManagerInterface $storeManager,
    \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollection,
    \Magento\Catalog\Model\Category $categoryModel,
    \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $factoryCollection,
    \Magento\Catalog\Model\Product $productModel
    )
    {
        $this->storeManager = $storeManager;
        $this->categoryCollection = $categoryCollection;
        $this->categoryModel = $categoryModel;
        $this->factoryCollection = $factoryCollection;
        $this->productModel = $productModel;
    }

    /**
     * Write to excludeArch.log
     *
     * @return void
     */
    public function execute()
    {
        try {
            $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/excludeArch.log');
            $logger = new \Zend_Log();
            $logger->addWriter($writer);
            $logger->info("Exclude process for categories started");

            //Exclude architecture categories
            $catCollection = $this->categoryCollection->create()
            ->addAttributeToSelect('*')
            ->setStore($this->storeManager->getStore())
            ->addAttributeToFilter('is_architech_data','1');

            if (count($catCollection) > 0) {
                foreach ($catCollection as $cat) {
                    $catId = $cat->getId();
                    $category = $this->categoryModel->load($catId);
                    $category->setInXmlSitemap(0)
                    ->setInHtmlSitemap(0)
                    ->setMetaRobots('NOINDEX, NOFOLLOW');
                    $category->save();
                    $logger->info("Category with ID ".$catId." was updated successfully");
                }
            }
            $logger->info("Exclude process for categories ended");

            //Exclude architecture products
            $logger->info("Exclude process for products started");
            $prodCollection = $this->factoryCollection->create()
            ->addAttributeToSelect('*')
            ->addFieldToFilter('attribute_set_id','4')
            ->addAttributeToFilter('is_architech_data','1');

            if (count($prodCollection) > 0) {
                foreach ($prodCollection as $prod) {
                    $prodId = $prod->getId();
                    $product = $this->productModel->load($prodId);
                    $product->addAttributeUpdate('in_xml_sitemap','No', 0);
                    $product->addAttributeUpdate('in_html_sitemap','No', 0);
                    $product->addAttributeUpdate('meta_robots', 'NOINDEX, NOFOLLOW', 0);
                    $logger->info("Product with ID ".$prodId." was updated successfully");
                }
            }
            $logger->info("Exclude process for products ended");
        } catch (\Exception $e) {
            print_r($e->getMessage());
        }
    }
}
