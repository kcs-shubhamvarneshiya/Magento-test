<?php
declare(strict_types=1);

namespace Capgemini\PaperModel\Setup\Patch\Data;

use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\Module\Dir;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Module\Dir\Reader;
use Magento\Cms\Api\Data\BlockInterfaceFactory;
use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Cms\Api\Data\BlockInterface;
use Psr\Log\LoggerInterface;
use Magento\Store\Model\Store;

class CreatePdpPaperModelBlock implements DataPatchInterface
{
    const FOLDER = "sources";

    const FILENAME = 'pdp_paper_model_block.html';

    const MODULE_CODE = 'Capgemini_PaperModel';

    const BLOCK_IDENTIFIER = 'paper_model_template';

    /** @var ModuleDataSetupInterface */
    protected $moduleDataSetup;

    /** @var ResourceConnection */
    protected $resource;

    /**
     * @var File
     */
    private $driverFile;

    /**
     * @var Reader
     */
    private $reader;

    /**
     * @var BlockInterfaceFactory
     */
    private $blockFactory;

    /**
     * @var BlockRepositoryInterface
     */
    private $blockRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * PopulateInitialQuestions constructor.
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param ResourceConnection $resource
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        BlockRepositoryInterface $blockRepository,
        BlockInterfaceFactory $blockFactory,
        ResourceConnection $resource,
        File $driverFile,
        Reader $reader,
        LoggerInterface $logger
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->blockRepository = $blockRepository;
        $this->blockFactory = $blockFactory;
        $this->resource = $resource;
        $this->driverFile = $driverFile;
        $this->reader = $reader;
        $this->logger = $logger;
    }

    /**
     * @return array
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function getAliases(): array
    {
        return [];
    }

    /**
     * @return $this
     */
    public function apply(): self
    {
        try {
            $dir = $this->reader->getModuleDir(Dir::MODULE_ETC_DIR, self::MODULE_CODE);
            $file = $dir . DIRECTORY_SEPARATOR . self::FOLDER . DIRECTORY_SEPARATOR . self::FILENAME;
            $blockContent = $this->driverFile->fileGetContents($file);
            /** @var BlockInterface $block */
            $block = $this->blockFactory->create();
            $block->setTitle('Paper Model Template')
                ->setIdentifier(self::BLOCK_IDENTIFIER)
                ->setContent($blockContent)
                ->setIsActive(1)
                ->setData('stores', [Store::DEFAULT_STORE_ID]);
            $this->blockRepository->save($block);

        } catch (\Exception $e) {
            $this->logger->error("Appy Data Patch CreatePdpPaperModelBlock error: " . $e->getMessage());
        }
        return $this;
    }
}
