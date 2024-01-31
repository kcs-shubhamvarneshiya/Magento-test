<?php

namespace Lyonscg\RequisitionList\Helper;

use iio\libmergepdf\Merger;
use iio\libmergepdf\Pages;
use iio\libmergepdf\Driver\TcpdiDriver;
use iio\libmergepdf\Source\FileSource;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;


class PdfMerge extends \Magento\Framework\App\Helper\AbstractHelper
{
    const ATTR_SPECIFICATIONS = 'ts_docname';

    const DOC_FOLDER = 'docs';

    /**
     * @var Filesystem
     */
    protected $_filesystem;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        Filesystem $filesystem
    ) {
        parent::__construct($context);
        $this->_filesystem = $filesystem;
    }

    /**
     * @param string $rawPdf
     * @param array $products
     * @param boolean $interlace
     * @return string
     */
    public function mergeForProducts($rawPdf, array $products, $interlace)
    {
        // TODO - if it is possible for there to be more than one page per product in detail mode
        // then we can get interlacing to work by generating the PDF one page at a time, and merging them
        // one at a time.
        if (empty($products)) {
            return $rawPdf;
        }

        $merger = new Merger(new TcpdiDriver());

        if (!$interlace) {
            $merger->addRaw($rawPdf);
        }
        $pageNum = 1;
        foreach ($products as $product) {
            if ($interlace) {
                $pages = new Pages(strval($pageNum));
                $merger->addRaw($rawPdf, $pages);
            }
            $pageNum++;

            /** @var ProductInterface $product */
            $specSheet = $this->_getSpecSheet($product);
            if (!$specSheet) {
                continue;
            }
            // we have a valid file, need to load it and merge with our PDF
            $merger->addFile($specSheet);
        }

        return $merger->merge();
    }

    protected function _getSpecSheet(ProductInterface $product)
    {
        $fileName = $product->getData(self::ATTR_SPECIFICATIONS);
        if (!$fileName) {
            return false;
        }

        $media = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA);
        $fullFileName = self::DOC_FOLDER . '/' . $fileName;

        if ($media->isFile($fullFileName)) {
            return $media->getAbsolutePath($fullFileName);
        } else {
            return false;
        }
    }
}
