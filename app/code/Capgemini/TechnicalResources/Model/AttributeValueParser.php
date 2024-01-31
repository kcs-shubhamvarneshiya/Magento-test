<?php
/**
 * Capgemini_TechnicalResources
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\TechnicalResources\Model;

use Capgemini\TechnicalResources\Helper\Data;
use Exception;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Driver\File;
use Psr\Log\LoggerInterface;

class AttributeValueParser
{
    public const VALUE_SEPARATOR = ",";
    public const MINIMUM_FILE_PARTS = 2;

    /**
     * @var File
     */
    protected File $fileDriver;

    /**
     * @var Data
     */
    protected Data $helper;

    /**
     * @var DirectoryList
     */
    protected DirectoryList $directoryList;

    /**
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * @param Data $helper
     * @param File $fileDriver
     * @param DirectoryList $directoryList
     * @param LoggerInterface $logger
     */
    public function __construct(
        Data            $helper,
        File            $fileDriver,
        DirectoryList   $directoryList,
        LoggerInterface $logger
    )
    {
        $this->helper = $helper;
        $this->fileDriver = $fileDriver;
        $this->directoryList = $directoryList;
        $this->logger = $logger;
    }

    /**
     * @param Product|ProductInterface $product
     * @return array
     */
    public function process(Product $product): array
    {
        $resources = [];
        $value = $product->getData($this->helper->getAttributeCode());
        if ($value) {
            $files = explode(self::VALUE_SEPARATOR, $value);
            foreach ($files as $file) {
                try {
                    if ($this->checkAvailability($file)) {
                        $resources[] = $this->parseTechResourceFile($file);
                    }
                } catch (Exception $e) {
                    $this->logger->critical($e->getMessage());
                }
            }
        }
        return $resources;
    }

    /**
     * @param string $file
     * @return bool
     * @throws FileSystemException
     */
    private function checkAvailability(string $file): bool
    {
        return $this->fileDriver->isExists($this->getFilePath($file));
    }

    /**
     * @param string $file
     * @return string
     */
    public function getFilePath(string $file): string
    {
        $dirPath = $this->directoryList->getRoot();

        $filePath = $this->helper->getResourcePath();

        return $dirPath . '/' . $filePath . '/' . $file;
    }

    /**
     * @param string $file
     * @return array
     * @throws Exception
     */
    private function parseTechResourceFile(string $file): array
    {
        $capitalize = $this->helper->getCapitalize();
        $pathParts = explode('/', $file);
        if (count($pathParts) < self::MINIMUM_FILE_PARTS) {
            throw new Exception('Invalid technical resource path: ' . $file);
        }
        $label = preg_replace_callback(
            '`' . $capitalize . '`',
            function ($matches) {
                return strtoupper($matches[0]);
            },
            $pathParts[0]
        );
        $label = ucwords(str_replace('_', ' ', $label), ' -');

        return [
            'label' => $label,
            'filepath' => $file,
        ];
    }
}
