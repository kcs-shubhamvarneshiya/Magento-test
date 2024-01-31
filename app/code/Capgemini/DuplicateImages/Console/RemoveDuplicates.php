<?php

namespace Capgemini\DuplicateImages\Console;

use Exception;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\State;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Store\Model\StoreManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class RemoveDuplicates extends Command
{
    const OPTION_SKUS = 'skus';
    const OPTION_LIMIT = 'limit';
    const ERROR_REPORTING_FILE = 'var/duplicate_images_removal_errors.txt';
    const BATCH_SIZE = 100;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var DirectoryList
     */
    private $directoryList;
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var string
     */
    private $mediaPath;
    /**
     * @var State
     */
    private $appState;
    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var int
     */
    private $limitRemainder;
    /**
     * @var array
     */
    private $failedSkus = [];
    /**
     * @var int
     */
    private $processedProductsCount = 0;

    /**
     * @param StoreManagerInterface $storeManager
     * @param DirectoryList $directoryList
     * @param CollectionFactory $collectionFactory
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        DirectoryList $directoryList,
        CollectionFactory $collectionFactory,
        ProductRepositoryInterface $productRepository,
        State $appState,
        Filesystem $filesystem
    ) {
        parent::__construct();
        $this->storeManager = $storeManager;
        $this->directoryList = $directoryList;
        $this->collectionFactory = $collectionFactory;
        $this->productRepository = $productRepository;
        $this->appState = $appState;
        $this->filesystem = $filesystem;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->appState->setAreaCode('adminhtml');
            $this->mediaPath = $this->directoryList->getPath('media');
        } catch (LocalizedException|FileSystemException $exception) {
            $output->writeln('An error happened while executing rhe command: ' . $exception->getLogMessage());

            return 1;
        }

        $this->storeManager->setCurrentStore(0);

        if ($skus = $input->getOption(self::OPTION_SKUS)) {
            $filters = $this->getSkuFilter($skus);
        }

        $limit = (int) $input->getOption(self::OPTION_LIMIT);
        $this->limitRemainder = $limit;

        if ($this->limitRemainder) {
            $collectionProcessCallback = function (Collection $productCollection, int $page) {
                return $this->processWithLimit($productCollection, $page);
            };
        } else {
            $collectionProcessCallback = function (Collection $productCollection, int $page) {
                return $this->processNoLimit($productCollection, $page);
            };
        }

        $page = 1;
        do {
            $productCollection = $this->collectionFactory->create();

            if (isset($filters)) {
                $productCollection->addFieldToFilter('sku', $filters);
            }
        } while ($collectionProcessCallback($productCollection, $page++) === self::BATCH_SIZE);
        $toBeProcessed = $limit ? min($productCollection->getSize(), $limit) : $productCollection->getSize();
        $output->writeln(
            sprintf(
                '%sTotal %d out of %d products has been successfully processed.',
                PHP_EOL,
                $this->processedProductsCount,
                $toBeProcessed
            )
        );

        if (!empty($this->failedSkus)) {
            $output->writeln(
                sprintf(
                    'Errors count: %d. For more details see %s.',
                    count($this->failedSkus),
                    self::ERROR_REPORTING_FILE
                )
            );
            try {
                $this->createErrorReport();
            } catch (FileSystemException $exception) {
                $output->writeln('Could not create ' . self::ERROR_REPORTING_FILE);
            }
        }

        return 0;
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $options = [
            new InputOption(
                self::OPTION_SKUS,
                null,
                InputOption::VALUE_OPTIONAL,
                'Comma separated values of SKUs. Wildcards excepted ("%"). If an SKU contains whitespaces put it in quotes.
                Example: --skus Some_common-SKU,SKU-with-wildcard%,"SKU with-whitespace".'
            ),
            new InputOption(
                self::OPTION_LIMIT,
                null,
                InputOption::VALUE_OPTIONAL,
                'Limits the number of products to be processed. Before processing the value is casted to integer.
                Zero or empty value is treated as infinity.'
            )
        ];

        $this->setName('duplicate-images:remove');
        $this->setDescription('Removes duplicate images of a product')
            ->setDefinition($options);

        parent::configure();
    }

    /**
     * @param string $skus
     * @return array
     */
    private function getSkuFilter(string $skus): array
    {
        $skus = explode(',', $skus);
        $filter = [];
        foreach ($skus as $datum) {
            $filter[] = ['like' => $datum];
        }

        return $filter;
    }

    /**
     * @param Collection $productCollection
     * @param int $page
     * @return int
     */
    private function processWithLimit(Collection $productCollection, int $page): int
    {
        if ($this->limitRemainder >= self::BATCH_SIZE) {
            $this->limitRemainder -= self::BATCH_SIZE;

            return $this->processNoLimit($productCollection, $page);
        } elseif ($this->limitRemainder === 0) {

            return 0;
        }

        $productCollection->getSelect()
            ->order('entity_id ASC')
            ->limit($this->limitRemainder, ($page - 1) * self::BATCH_SIZE);

        return $this->processCollection($productCollection);
    }


    /**
     * @param Collection $productCollection
     * @param int $page
     * @return int
     */
    private function processNoLimit(Collection $productCollection, int $page): int
    {
        $productCollection->setOrder('entity_id', 'ASC')
            ->setPageSize(self::BATCH_SIZE)
            ->setCurPage($page);

        return $this->processCollection($productCollection);
    }

    /**
     * @param Collection $productCollection
     * @return int
     */
    private function processCollection(Collection $productCollection): int
    {
        $processed = 0;
        foreach ($productCollection as $item) {
            try {
                echo '.';
                $product = $this->productRepository->getById($item->getId());
                $md5Values = [];
                $hasChanged = false;

                $gallery = $product->getMediaGalleryEntries();
                if ($gallery) {
                    foreach ($gallery as $index => $galleryImage) {
                        $filepath = $this->mediaPath . '/catalog/product' . $galleryImage->getFile();

                        if(file_exists($filepath)) {
                            $md5 = $this->getHash($filepath);;
                        } else {
                            unset($gallery[$index]);
                            $hasChanged = true;

                            continue;
                        }

                        if(isset($md5Values[$md5])) {
                            if (count($galleryImage->getTypes()) > 0) {
                                if ($md5Values[$md5] !== 'first_appearance_checked') {
                                    unset($gallery[$md5Values[$md5]]);
                                    $hasChanged = true;
                                    $md5Values[$md5] = 'first_appearance_checked';
                                }

                                continue;
                            }

                            unset($gallery[$index]);
                            $hasChanged = true;
                        } else {
                            $md5Values[$md5] = count($galleryImage->getTypes()) > 0 ? 'first_appearance_checked' : $index;
                        }
                    }

                    if ($hasChanged) {
                        $product->setMediaGalleryEntries($gallery);
                        $this->productRepository->save($product);
                    }
                }
                $processed++;
            } catch (Exception $exception) {
                $this->failedSkus[$item->getSku()] = $exception->getMessage();
                $processed++;
            }
        }
        $this->processedProductsCount += $processed;

        return $processed;
    }

    /**
     * @return void
     * @throws FileSystemException
     */
    private function createErrorReport()
    {
        $varDirWrite = $this->filesystem->getDirectoryWrite($this->directoryList::VAR_DIR);
        $contents = '';
        foreach ($this->failedSkus as $sku => $exceptionMessage) {
            $contents .= $sku . ' => ' . $exceptionMessage . PHP_EOL;
        }
        $varDirWrite->writeFile(self::ERROR_REPORTING_FILE, $contents);
    }

    private function getHash($filepath)
    {
        $fileExtension = pathinfo($filepath, PATHINFO_EXTENSION);
        $imageCreatingFunction = 'imagecreatefrom' . $fileExtension;
        if (function_exists($imageCreatingFunction)) {
            ob_start ();
            imagegd2($imageCreatingFunction($filepath));
            $imageData = ob_get_contents ();
            ob_end_clean ();
        } else {
            $imageData = file_get_contents($filepath);
        }

        return md5($imageData);
    }
}
