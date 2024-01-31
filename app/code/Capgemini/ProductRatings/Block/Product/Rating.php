<?php
/**
 * Capgemini_ProductRatings
 * php version 7.4.27
 *
 * @category  Capgemini
 * @package   Capgemini_ProductRatings
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link      https://www.capgemini.com
 */
declare(strict_types=1);

namespace Capgemini\ProductRatings\Block\Product;

use Magento\Catalog\Block\Product\View\Description;
use Magento\Catalog\Model\Product;
use Magento\Framework\App\Filesystem\DirectoryList as AppDirectoryList;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template\Context;

/**
 * Short description
 *
 * @category  Capgemini
 * @package   Capgemini_ProductRatings
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link      https://www.capgemini.com
 */
class Rating extends Description
{
    public const IMG_DIR = 'ratings';
    public const IMG_EX = 'png';

    /**
     * Short description
     *
     * @var File
     */
    protected File $fileDriver;

    /**
     * Short description
     *
     * @var DirectoryList
     */
    protected DirectoryList $directoryList;

    /**
     * Short description
     *
     * @param Context $context Param description
     * @param Registry $registry Param description
     * @param File $fileDriver Param description
     * @param DirectoryList $directoryList Param description
     */
    public function __construct(
        Context $context,
        Registry $registry,
        File $fileDriver,
        DirectoryList $directoryList
    ) {
        $this->_coreRegistry = $registry;
        $this->fileDriver = $fileDriver;
        $this->directoryList = $directoryList;
        parent::__construct($context, $registry);
    }

    /**
     *  Short description
     *
     * @return array
     */
    public function getRatingsData(): array
    {
        try {
            $product = $this->getProduct();
            $ratingsData = [];
            if ($product) {
                $ratings = $product->getRating();
                if ($ratings) {
                    $ratingsArray = explode(',', $ratings);
                    foreach ($ratingsArray as $rating) {
                        $ratingData = [];
                        $ratingData['label'] = $rating;
                        $fileName = strtolower(
                            str_replace(' ', '_', trim($rating))
                        );

                        if ($this->fileDriver->isExists($this->getImagePath($fileName))) {
                            $ratingData['img'] = $this->getWebImagePath($fileName);
                        }

                        $ratingsData[] = $ratingData;
                    }
                }
            }

            return $ratingsData;
        } catch (\Exception $exception) {
            return [];
        }
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        if ($this->getData('product')){
            return $this->getData('product');
        } else {
            return parent::getProduct();
        }
    }

    /**
     * Short description
     *
     * @param string $fileName
     *
     * @return string
     *
     * @throws FileSystemException
     */
    protected function getImagePath(string $fileName): string
    {
        $dirPath = $this->directoryList->getPath(AppDirectoryList::MEDIA);

        return $this->_getFullPath($dirPath, $fileName);
    }

    /**
     * Short description
     *
     * @param string $fileName
     *
     * @return string
     */
    protected function getWebImagePath(string $fileName): string
    {
        $dirPath = $this->directoryList->getUrlPath(AppDirectoryList::MEDIA);

        return $this->_getFullPath($dirPath, $fileName);
    }

    /**
     * Short description
     *
     * @param string $dirPath
     * @param string $fileName
     *
     * @return string
     */
    private function _getFullPath(string $dirPath, string $fileName): string
    {
        return $dirPath.'/'.self::IMG_DIR.'/'.$fileName.'.'.self::IMG_EX;
    }
}
