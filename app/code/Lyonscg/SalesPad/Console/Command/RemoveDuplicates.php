<?php

namespace Lyonscg\SalesPad\Console\Command;

use Lyonscg\SalesPad\Model\ResourceModel\CustomerLink\Collection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Exception\FileSystemException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Lyonscg\SalesPad\Model\ResourceModel\CustomerLink\CollectionFactory;
use Magento\Framework\App\ResourceConnection;
use Zend_Db_Statement_Exception;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;
use Symfony\Component\Console\Input\InputOption;

class RemoveDuplicates extends Command
{
    const BATCH_SIZE = 100;
    const ERROR_REPORTING_FILE = 'sp_customer_link_duplicates_report.txt';
    const OPTION_DRY_RUN = 'dry-run';

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var AdapterInterface
     */
    private $connection;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var array
     */
    private $onlyNullIdSet;

    /**
     * @var array
     */
    private $notOnlyNullIdSet;

    /**
     * @param CollectionFactory $collectionFactory
     * @param ResourceConnection $resourceConnection
     * @param string|null $name
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        ResourceConnection $resourceConnection,
        Filesystem $filesystem,
        string $name = null
    ) {
        parent::__construct();

        $this->collectionFactory = $collectionFactory;
        $this->connection = $resourceConnection->getConnection();
        $this->filesystem = $filesystem;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $dryRun = $input->getOption(self::OPTION_DRY_RUN) !== '0';
        $dryRunText = $dryRun ? ' (Executing Dry Run.)' : '';
        $output->writeln('---Starting the Process' . $dryRunText . '---');
        $output->writeln('Figuring out Records to Delete.');
        $page = 1;
        do {
            $collection = $this->collectionFactory->create()
                ->setPageSize(self::BATCH_SIZE)
                ->setCurPage($page++)
                ->setOrder('link_id');
            $output->write('-');
        } while ($this->figureOutEntriesToDelete($collection) === self::BATCH_SIZE);

        $tableName = $this->connection->getTableName('salespad_customer_link');
        $deletedCount = 0;
        $output->writeln(PHP_EOL . 'Deleting Figured out Records.');

        $this->connection->beginTransaction();
        foreach ($this->onlyNullIdSet as $idsToDelete) {
            array_pop($idsToDelete);
            $deletedCount += $this->deleteRecords($tableName, $idsToDelete);
            $output->write('.');
        }
        foreach ($this->notOnlyNullIdSet as $idsToDelete) {
            $deletedCount += $this->deleteRecords($tableName, $idsToDelete);
            $output->write('.');
        }
        $output->writeln(PHP_EOL . 'Totally deleted: ' . $deletedCount);
        try {
            $this->reportRemainingDuplicates($tableName);
        } catch (Zend_Db_Statement_Exception|FileSystemException $e) {
            $output->writeln('The report on undeleted duplicates has not been created due to an error.');
        }
        $dryRun ? $this->connection->rollBack() : $this->connection->commit();

        $output->writeln('---Process Ended---');

        return 0;
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $options = [
            new InputOption(
                self::OPTION_DRY_RUN,
                null,
                InputOption::VALUE_OPTIONAL,
                'If not equal to "0" the command runs without deleting records in reality. Defaults to 1.',
                1
            )
        ];
        $this->setName('duplicate-sp-customer-links:remove')
            ->setDescription('Removes duplicate records from salespad_customer_link table')
            ->setDefinition($options);


        parent::configure();
    }

    /**
     * @param Collection $collection
     * @return int
     */
    private function figureOutEntriesToDelete(Collection $collection): int
    {
        foreach ($collection as $item)
        {
            $identifier = sprintf(
                '%s,%s,%s',
                strtolower($item->getData('customer_email')),
                $item->getData('website_id'),
                $item->getData('sales_pad_customer_num')
            );

            if ($item->getData('customer_id')) {
                if (isset($this->onlyNullIdSet[$identifier])) {
                    $this->notOnlyNullIdSet[$identifier] = $this->onlyNullIdSet[$identifier];
                    unset($this->onlyNullIdSet[$identifier]);
                } elseif (!isset($this->notOnlyNullIdSet[$identifier])) {
                    $this->notOnlyNullIdSet[$identifier] = [];
                }
            } else {
                if (isset($this->notOnlyNullIdSet[$identifier])) {
                    $this->notOnlyNullIdSet[$identifier][] = $item->getData('link_id');
                } else {
                    $this->onlyNullIdSet[$identifier][] = $item->getData('link_id');
                }
            }
        }

        return $collection->count();
    }

    /**
     * @param $tableName
     * @param $idsToDelete
     * @return int
     */
    private function deleteRecords($tableName, $idsToDelete): int
    {
        if ($in = implode(',', $idsToDelete)) {
            $where = sprintf('link_id IN(%s)', $in);

            return $this->connection->delete(
                $tableName,
                [$where]
            );
        }

        return 0;
    }

    /**
     * @param $tableName
     * @return void
     * @throws Zend_Db_Statement_Exception
     * @throws FileSystemException
     */
    private function reportRemainingDuplicates($tableName)
    {
        $select = $this->connection->select()
            ->from([$tableName], ['customer_email', 'website_id'])
            ->group(['customer_email', 'website_id'])
            ->having('COUNT(*) > 1');
        $stmt = $this->connection->query($select);
        $in = $stmt->fetchAll();
        $customerEmailIn = array_column($in, 'customer_email');
        $websiteIdIn = array_column($in, 'website_id');
        $select = $this->connection->select()
            ->from($tableName, ['*'])
            ->where('customer_email IN(?)', $customerEmailIn)
            ->where('website_id IN(?)', $websiteIdIn)
            ->order('website_id ASC')
            ->order('customer_email ASC')
            ->order('link_id ASC');
        $stmt = $this->connection->query($select);
        $rows = $stmt->fetchAll();

        $varDirWrite = $this->filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
        $contents = 'Cases where there are several SP numbers per one email on the same website.' . PHP_EOL;
        $contents .= '  (link_id => sales_pad_customer_num)' . PHP_EOL;
        $lastWebsiteId = null;
        $lastCustomerEmail = '';
        foreach ($rows as $row) {
            if ($row['website_id'] !== $lastWebsiteId) {
                $contents .= PHP_EOL . 'Website ID: ' . $row['website_id'] . PHP_EOL;
                $lastWebsiteId = $row['website_id'];
            }

            $lowerCasedEmail = strtolower($row['customer_email']);
            if ($lowerCasedEmail !== $lastCustomerEmail) {
                $contents .= PHP_EOL . '    ' . $lowerCasedEmail . PHP_EOL;
                $lastCustomerEmail = $lowerCasedEmail;
            }

            $contents .= '      ' . $row['link_id'] . ' => ' . $row['sales_pad_customer_num'];

            if ($row['customer_id'] !== null) {
                $contents .= ' (customer_id = ' . $row['customer_id'] . ')';
            }

            $contents .= PHP_EOL;
        }
        $varDirWrite->writeFile(self::ERROR_REPORTING_FILE, $contents);
    }
}
