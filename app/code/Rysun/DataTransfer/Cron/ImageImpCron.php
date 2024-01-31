<?php

namespace Rysun\DataTransfer\Cron;

use Rysun\DataTransfer\Helper\Data;
use Rysun\DimensionImages\Model\ResourceModel\DimensionImages as ResourceDimensionImages;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

class ImageImpCron
{
    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var ResourceDimensionImages
     */
    protected $resource;

    /**
     * @var ProductFactory
     */
    protected $productFactory;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productApiRepository;

    /**
     * @var ResourceConnection
     */
    protected $resourceConnection;

    /**
     * @var CollectionFactory
     */
    protected $productCollection;

    const IMAGE_IMPORT_PATH = 'archi_import/general/product_image_import_path';

    /**
     * @param Data $helper
     * @param ResourceDimensionImages $resource
     * @param ProductFactory $productFactory
     */
    public function __construct(
        Data $helper,
        ResourceDimensionImages $resource,
        ProductFactory $productFactory,
        ProductRepositoryInterface $productApiRepository,
        ResourceConnection $resourceConnection,
        CollectionFactory $productCollection
    )
    {
        $this->helper = $helper;
        $this->resource = $resource;
        $this->productFactory = $productFactory;
        $this->productApiRepository = $productApiRepository;
        $this->resourceConnection = $resourceConnection;
        $this->productCollection = $productCollection;
    }

    /**
     * Write to imageImport.log
     *
     * @return void
     */
    public function execute()
    {
        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/imageImport.log');
        $logger = new \Zend_Log();
        $logger->addWriter($writer);
        $logger->info("Start product image import process");
        try {
            $filePath = $this->helper->getConfigValue(SELF::IMAGE_IMPORT_PATH);
            $csv = array_map("str_getcsv", file($filePath, FILE_SKIP_EMPTY_LINES));
            //print_r($csv); exit;
            $keys = array_shift($csv);
            $err = "";
            foreach ($csv as $i => $row) {
                if (count($row) > 0) {
                    // Check for discrepancies between the amount of headers and the amount of rows
                    if (count($row) !== count($keys)) {
                        $err .= "<br>Row " . $i . "\'s length does not match the header length: " . implode(', ', $row);
                    } else {
                        $csv[$i] = array_combine($keys, $row);
                        if (empty($csv[$i]['csv_action'])
                            || empty($csv[$i]['sql_serv_id'])
                            || empty($csv[$i]['sql_serv_prod_id'])
                            || empty($csv[$i]['image_url'])
                            || empty($csv[$i]['image_file_name'])
                            || empty($csv[$i]['image_type'])
                        ) {
                            $err .= "<br>Row " . $i . " hase empty field please check.";
                        }
                    }
                }
            }

                $tableName = 'rysun_dimensionimages_dimensionimages';

                foreach ($csv as $i => $row) {

                    if ($row['csv_action'] == 'C') {
                        //Add main images to product
                        $sqlServerId = $row['sql_serv_prod_id'];
                        $id = $this->getProductIdBySqlServerId($sqlServerId);
                        if ($row['image_type'] == 'Main') {
                        $product = $this->productFactory->create();
                        $product->load($id);
                        if ($product->getId()) {
                        $imagePath = BP."/".$row['image_url'].$row['image_file_name'];
                        if ($row['display_order'] == '1') {
                        $product->addImageToMediaGallery($imagePath, array('image', 'small_image', 'thumbnail'), false, false);
                        } else {
                        $product->addImageToMediaGallery($imagePath, array('small_image', 'thumbnail'), false, false);
                        }
                        $product->save();
                        $logger->info("Main image for product with ID ".$id." got added successfully");
                        }
                        }
                        //Add dimension images in custom table
                        if ($row['image_type'] == 'Dimension') {
                        $connection = $this->resourceConnection->getConnection();
                        $data = array('sql_serv_prod_id' => $row['sql_serv_prod_id'], 'image_id' => $row['image_id'], 
                            'image_caption' => $row['image_caption'], 'image_description' => $row['image_description'], 
                            'image_url' => $row['image_url'], 'image_file_name' => $row['image_file_name'], 
                            'image_alt_tag' => $row['image_alt_tag'], 'image_type' => $row['image_type'], 
                            'is_active' => $row['is_active']);
                        $connection->insert($tableName, $data);
                        $logger->info("Dimension image for product with ID ".$id." got added successfully");
                        }

                    } else if ($row['csv_action'] == 'U' && $row['sql_serv_prod_id'] !== '') {
                        //Update main images
                        $sqlServerId = $row['sql_serv_prod_id'];
                        $id = $this->getProductIdBySqlServerId($sqlServerId);
                        if ($row['image_type'] == 'Main') {
                        $product = $this->productFactory->create();
                        $product->load($id);
                        if ($product->getId()) {
                        $imagePath = BP."/".$row['image_url'].$row['image_file_name'];
                        if ($row['display_order'] == '1') {
                        $product->addImageToMediaGallery($imagePath, array('image', 'small_image', 'thumbnail'), false, false);
                        } else {
                        $product->addImageToMediaGallery($imagePath, array('small_image', 'thumbnail'), false, false);
                        }
                        $product->save();
                        $logger->info("Main image for product with ID ".$id." got updated successfully");
                        }
                        }
                        //Update dimension images
                        if ($row['image_type'] == 'Dimension') {
                        $connection = $this->resourceConnection->getConnection();
                        $table = $connection->getTableName($tableName);
                        $query = "UPDATE $table SET image_caption = \"" . $row['image_caption'] . "\", image_description = \"" . $row['image_description'] . "\", image_url = \"" . $row['image_url'] . "\", image_file_name = \"" . $row['image_file_name'] . "\", image_alt_tag = \"" . $row['image_alt_tag'] . "\", image_type = \"" . $row['image_type'] . "\", is_active = \"" . $row['is_active'] . "\" WHERE sql_serv_prod_id = \"" . $row['sql_serv_prod_id'] . "\" and image_id = \"" . $row['image_id'] . "\";";
                        $connection->query($query);
                        $logger->info("Dimension image for product with ID ".$id." got updated successfully");
                        }

                    } else if ($row['csv_action'] == 'D' && $row['sql_serv_prod_id'] !== '') {
                        //Delete main product images
                        $sqlServerId = $row['sql_serv_prod_id'];
                        $id = $this->getProductIdBySqlServerId($sqlServerId);
                        if ($row['image_type'] == 'Main') {
                        $sqlServerId = $row['sql_serv_prod_id'];
                        $id = $this->getProductIdBySqlServerId($sqlServerId);
                        $product = $this->productFactory->create();
                        $product->load($id);
                        if ($product->getId()) {
                        $existingMediaGalleryEntries = $product->getMediaGalleryEntries();
                        foreach ($existingMediaGalleryEntries as $key => $entry) {
                            unset($existingMediaGalleryEntries[$key]);
                        }
                        $product->setMediaGalleryEntries($existingMediaGalleryEntries);
                        $this->productApiRepository->save($product);
                        $logger->info("Main image for product with ID ".$id." got deleted successfully");
                        }
                        }
                        //Delete dimensions images
                        if ($row['image_type'] == 'Dimension') {
                        $connection = $this->resourceConnection->getConnection();
                        $table = $connection->getTableName($tableName);
                        $query = "DELETE FROM $table WHERE sql_serv_prod_id = \"" . $row['sql_serv_prod_id'] . "\" and image_id = \"" . $row['image_id'] . "\";";
                        $connection->query($query);
                        $logger->info("Dimension image for product with ID ".$id." got deleted successfully");
                        }
                    }
                }
        } catch (Exception $e) {
            $error_message = "Unable to read csv file. Error: " . $e->getMessage() . '. See exception.log for full error log.';
            $this->messageManager->addError($error_message);
            $logger->info($e);
        }
        $logger->info("End product image import process");
    }


    /**
     * Find magento product id from sql server id value
     */
    public function getProductIdBySqlServerId($sqlServerId)
    {

        $collection = $this->productCollection->create();
        $collection->addAttributeToSelect('sql_serv_id');
        $collection->addAttributeToSelect('sku');
        $collection->addAttributeToFilter('sql_serv_id', ['eq' => $sqlServerId]);
        if ($collection->getSize()) {
            $data = $collection->getFirstItem();
            $id = $data->getId();
            return $id;
        }
        return null;
    }

}
