<?php

namespace Lyonscg\SalesPad\Console\Command;

use Exception;
use Lyonscg\SalesPad\Console\Command\CorrectCustomerLinks\Validator;
use Lyonscg\SalesPad\Model\CustomerLinkRepository;
use Lyonscg\SalesPad\Model\ResourceModel\CustomerLink\CollectionFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\ValidatorException;
use Magento\Framework\File\Csv;
use Magento\Framework\Filesystem;
use Magento\Framework\Registry;
use Magento\Store\Model\StoreManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CorrectCustomerLinks extends Command
{
    const INPUT_OPTIONS = [
        'SOURCE_FILE'       => [
            'name'     => 'file',
            'shortcut' => 'f'
        ],
        'WEBSITE'           => [
            'name'     => 'website',
            'shortcut' => 'w'
        ],
        'STRICT_COMPARISON' => [
            'name'     => 'strict-comparison',
            'shortcut' => 's'
        ],
        'REMOVE_CUSTOMER'   => [
            'name'     => 'remove-customer',
            'shortcut' => 'r'
        ],
        'DRY_RUN'           => [
            'name'     => 'dry-run',
            'shortcut' => 'd'
        ]
    ];
    const BOOLEAN_VALUES = [
        'yes'   => true,
        'true'  => true,
        '1'     => true,
        'no'    => false,
        'false' => false,
        '0'     => false
    ];
    const REMOVE_MARKS = [
        'Remove',
        'remove'
    ];
    const DATA_KEYS = [
        'simple' => [
            'customer_email',
            'correct_to'
        ],
        'composite' => [
            'link_id',
            'sales_pad_customer_num',
            'customer_id',
        ]
    ];
    const REPORT_CATEGORIES = [
        100 => 'skipped',
        200 => 'unable_to_delete',
        300 => 'unable_to_add',
        400 => 'other'
    ];
    const ERROR_REPORTING_FILE = 'sp_customer_link_correction_report.txt';
    /**
     * @var Csv
     */
    private $csv;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;
    /**
     * @var CustomerLinkRepository
     */
    private $linkRepository;
    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var AdapterInterface
     */
    private $connection;
    /**
     * @var array
     */
    private $mainStructure;
    /**
     * @var array
     */
    private $partialAuxiliaryStructure;
    /**
     * @var int
     */
    private $columnsNumber;
    /**
     * @var int
     */
    private $websiteId;
    /**
     * @var bool
     */
    private $isUseStrictComparison;
    /**
     * @var bool
     */
    private $shouldRemoveCustomer;
    /**
     * @var Validator
     */
    private $validator;
    /**
     * @var int
     */
    private $totalRowsCount;
    /**
     * @var array
     */
    private $toSkip = [];
    /**
     * @var int
     */
    private $successCounter = 0;
    /**
     * @var array
     */
    private $reportEntries = [
        self::REPORT_CATEGORIES[100] => [],
        self::REPORT_CATEGORIES[200] => [],
        self::REPORT_CATEGORIES[300] => [],
        self::REPORT_CATEGORIES[400] => []
    ];


    public function __construct(
        StoreManagerInterface       $storeManager,
        Csv                         $csv,
        CustomerRepositoryInterface $customerRepository,
        CustomerLinkRepository      $linkRepository,
        Filesystem                  $filesystem,
        ResourceConnection          $resourceConnection,
        CollectionFactory           $collectionFactory,
        Registry                    $coreRegistry,
        string                      $name = null
    ) {
        parent::__construct($name);

        $this->csv = $csv;
        $this->storeManager = $storeManager;
        $this->customerRepository = $customerRepository;
        $this->linkRepository = $linkRepository;
        $this->filesystem = $filesystem;
        $this->connection = $resourceConnection->getConnection();
        $this->validator = new Validator(
            $this->websiteId,
            $this->isUseStrictComparison,
            $this->columnsNumber,
            $collectionFactory
        );
        $coreRegistry->register('isSecureArea', true);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws LocalizedException
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $sourceFile = $input->getOption(self::INPUT_OPTIONS['SOURCE_FILE']['name']);

        if (null === $sourceFile || !file_exists($sourceFile)) {
            $output->writeln('The source file was not provided or was not found!');

            return 1;
        }

        $websiteIdentifier = $input->getOption(self::INPUT_OPTIONS['WEBSITE']['name']);

        try {
            $this->websiteId = $this->storeManager->getWebsite($websiteIdentifier)->getId();
        } catch (Exception $exception) {
            $output->writeln(
                sprintf(
                    'Could not retrieve website with the provided identifier of %s: %s',
                    $websiteIdentifier,
                    $exception->getMessage()
                )
            );

            return 1;
        }

        $isUseStrictComparison = $input->getOption(self::INPUT_OPTIONS['STRICT_COMPARISON']['name']);
        $this->validator->validateBooleanOption(self::INPUT_OPTIONS['STRICT_COMPARISON']['name'], $isUseStrictComparison);
        $this->isUseStrictComparison = self::BOOLEAN_VALUES[$isUseStrictComparison];

        $shouldRemoveCustomer = $input->getOption(self::INPUT_OPTIONS['REMOVE_CUSTOMER']['name']);
        $this->validator->validateBooleanOption(self::INPUT_OPTIONS['REMOVE_CUSTOMER']['name'], $shouldRemoveCustomer);
        $this->shouldRemoveCustomer = self::BOOLEAN_VALUES[$shouldRemoveCustomer];

        try {
            $records = $this->prepareData($this->csv->getData($sourceFile));

            $emails = array_column($records, 'customer_email');
            $this->validator->validateValuesUniqueness(
                'customer_email',
                $emails
            );

            $correctSpNumbers = array_column($records, 'correct_to');
            $this->validator->validateValuesUniqueness(
                'correct_to',
                $correctSpNumbers,
                self::REMOVE_MARKS
            );
        } catch (Exception $exception) {
            $output->writeln(
                sprintf(
                    'There is an issues with the source file or with its data: %s %s',
                    PHP_EOL,
                    $exception->getMessage()
                )
            );

            return 1;
        }

        $dryRun = $input->getOption(self::INPUT_OPTIONS['DRY_RUN']['name']);
        $this->validator->validateBooleanOption(self::INPUT_OPTIONS['DRY_RUN']['name'], $dryRun);
        $dryRun = self::BOOLEAN_VALUES[$dryRun];
        $dryRunText = $dryRun ? ' (Executing Dry Run.)' : '';
        $output->writeln('---Starting the Process' . $dryRunText . '---');

        foreach ($records as $index => $record) {
            if (in_array($index, $this->toSkip)) {

                continue;
            }

            try {
                $this->validator->validateAgainstDb($record);
            } catch (ValidatorException $exception) {
                $this->toSkip[] = $index;
                $this->createReportEntry($index + 1,  $exception->getMessage(), 100);

                continue;
            }

            $this->connection->beginTransaction();
            $this->processRow($index + 1, $record);
            $output->write('.');
            $dryRun ? $this->connection->rollBack() : $this->connection->commit();
        }

        try {
            if (!$this->generateReport()) {
                $output->writeln(PHP_EOL . 'Nothing to report, the report file has not been generated.');
            } else {
                $output->writeln(PHP_EOL . 'Report file has been generated: ' . self::ERROR_REPORTING_FILE);
            }
        } catch (FileSystemException $exception) {
            $output->writeln('The report on the command execution results has not been created due to an error.');
        }

        $output->writeln('---Process Ended---');

        return 0;
    }

    protected function configure()
    {
        $options = [
            new InputOption(
                self::INPUT_OPTIONS['SOURCE_FILE']['name'],
                self::INPUT_OPTIONS['SOURCE_FILE']['shortcut'],
                InputOption::VALUE_REQUIRED,
                'The file based on what the customer links will be corrected.'
            ),
            new InputOption(
                self::INPUT_OPTIONS['WEBSITE']['name'],
                self::INPUT_OPTIONS['WEBSITE']['shortcut'],
                InputOption::VALUE_OPTIONAL,
                'Website ID',
                1
            ),
            new InputOption(
                self::INPUT_OPTIONS['STRICT_COMPARISON']['name'],
                self::INPUT_OPTIONS['STRICT_COMPARISON']['shortcut'],
                InputOption::VALUE_OPTIONAL,
                'Comparison type used in array search when validating source file against DB.',
                1
            ),
            new InputOption(
                self::INPUT_OPTIONS['REMOVE_CUSTOMER']['name'],
                self::INPUT_OPTIONS['REMOVE_CUSTOMER']['shortcut'],
                InputOption::VALUE_OPTIONAL,
                'When equal false if a registered customer by a given email exists it will be deleted.',
                0
            ),
            new InputOption(
                self::INPUT_OPTIONS['DRY_RUN']['name'],
                self::INPUT_OPTIONS['DRY_RUN']['shortcut'],
                InputOption::VALUE_OPTIONAL,
                'If not equal true the command runs without correcting records in reality. Defaults to 1.',
                1
            )
        ];
        $this->setName('sp-customer-links:correct')
            ->setDescription('Corrects records from salespad_customer_link table based on provided CSV file.')
            ->setDefinition($options);


        parent::configure();
    }

    /**
     * @param array $data
     * @return array
     * @throws ValidatorException
     */
    private function prepareData(array $data): array
    {
        $columnNames = array_shift($data);
        $this->totalRowsCount = count($data);
        list($this->mainStructure, $this->partialAuxiliaryStructure) = $this->obtainStructure($columnNames);
        $records = [];
        foreach ($data as $index => $row) {
            try {
                $correctTo = $row[$this->mainStructure['correct_to']];
                $this->validator->validateNonEmptyCorrectToValue($correctTo);
                $this->validator->validateColumnsCount($row);
            } catch (ValidatorException $exception) {
                $this->toSkip[] = $index;
                $this->createReportEntry($index + 1, $exception->getMessage(), 100);
            }
            $records[] = $this->associateRowData($row);
        }

        return $records;
    }

    /**
     * @param array $columnNames
     * @return array
     * @throws ValidatorException
     */
    private function obtainStructure(array $columnNames): array
    {
        $main = [];
        $auxiliary = [];
        foreach ($columnNames as $index => $columnName) {
            $lastUnderscorePos = strrpos($columnName, '_');

            if ($lastUnderscorePos !== false) {
                $lastPart = substr($columnName, $lastUnderscorePos + 1);

                if (!is_numeric($lastPart)) {
                    $this->validator->validateKey($columnName);
                    $main[$columnName] = $index;

                    continue;
                }

                $intCasted = intval($lastPart);

                if ($intCasted != $lastPart) {
                    $this->validator->validateKey($columnName);
                    $main[$columnName] = $index;

                    continue;
                }

                $key = substr($columnName, 0, $lastUnderscorePos - strlen($columnName));
                $this->validator->validateKey($key, 'composite');
                $main[$key][$intCasted] = $index;
                $auxiliary[$intCasted][$key] = $index;
            } else {
                $this->validator->validateKey($columnName);
            }
        }
        $this->validator->validateCompositeElements($main);
        $this->columnsNumber = ($index ?? -1) + 1;

        return [$main, $auxiliary];
    }

    /**
     * @param $row
     * @return array
     */
    private function associateRowData($row): array
    {
        $structure = $this->mainStructure;
        $auxiliary = $this->partialAuxiliaryStructure;
        $associated = [];
        foreach ($structure as $outerKey => $outerValue) {
            if (!is_array($outerValue)) {
                $associated[$outerKey] = trim($row[$outerValue]);

                continue;
            }

            foreach ($outerValue as $innerKey => $innerValue) {
                $testString = '';
                foreach ($auxiliary[$innerKey] as $val) {
                    $testString .= $row[$val];
                }

                if ($testString) {
                    $associated[$outerKey][$innerKey] = trim($row[$innerValue]);
                }
            }
        }

        return $associated;
    }

    /**
     * @throws LocalizedException
     */
    private function processRow($rowNum, $record)
    {
        try {
            $customer = $this->customerRepository->get($record['customer_email'], $this->websiteId);
            $customerId = $customer->getId();
        } catch (NoSuchEntityException $exception) {
            $this->reportRelatedEmails($rowNum, $record);
            $customerId = null;
        }

        $this->doCorrection($rowNum, $record, $customerId);
    }

    /**
     * @param $rowNum
     * @param $record
     * @return void
     */
    private function reportRelatedEmails($rowNum, $record)
    {
        $relatedEmails = [];
        foreach ($record['customer_id'] as $id) {
            try {
                $customer = $this->customerRepository->getById($id);
                $relatedEmails[] = $customer->getEmail();
            } catch (Exception $exception) {

                continue;
            }
        }

        if (!empty($relatedEmails)) {
            $this->createReportEntry(
                $rowNum,
                sprintf(
                    'The customer %1$s most likely does not exist.%3$sHowever, such emails as%3$s%2$s%3$smay be related to him/her.',
                    $record['customer_email'],
                    implode(', ', $relatedEmails),
                    PHP_EOL . '    '
                ),
                400
            );
        }
    }

    /**
     * @param int $rowNum
     * @param array $record
     * @param int|null $customerId
     * @return void
     */
    private function doCorrection(int $rowNum, array $record, ?int $customerId)
    {
        $isCorrect = in_array($record['correct_to'], self::REMOVE_MARKS);

        if ($isCorrect) {
            if ($this->shouldRemoveCustomer && $customerId) {
                if (!$this->removeCustomer($rowNum, $customerId)) {

                    return;
                }
            }

            foreach ($record['link_id'] as $linkId) {
                $linkIdToDelete = intval($linkId);
                if (!$this->deleteLink($rowNum, $linkIdToDelete)) {

                    return;
                }
            }
            $this->successCounter++;

            return;
        }

        $alterCandidateIndexes = [];
        foreach ($record['sales_pad_customer_num'] as $index => $spNum) {
            $condition = $this->isUseStrictComparison ? $record['correct_to'] !== $spNum : $record['correct_to'] != $spNum;
            if ($condition) {
                $linkIdToDelete = intval($record['link_id'][$index]);
                if (!$this->deleteLink($rowNum, $linkIdToDelete)) {

                    return;
                }
            } else {
                $alterCandidateIndexes[] = $index;
            }
        }

        if (!empty($alterCandidateIndexes)) {
            foreach ($alterCandidateIndexes as $alterCandidateIndex) {
                if (!$isCorrect &&
                    (int) $record['customer_id'][$alterCandidateIndex] === $customerId) {
                    $isCorrect = true;

                    continue;
                }

                $linkIdToDelete = intval($record['link_id'][$alterCandidateIndex]);
                if (!$this->deleteLink($rowNum, $linkIdToDelete)) {

                    return;
                }
            }

        }

        if (!$isCorrect && !$this->linkRepository->add(
                $customerId,
                $record['customer_email'],
                $this->websiteId,
                $record['correct_to']
            )
        ) {
            $this->createReportEntry(
                $rowNum,
                'Could not add link with a correct SP number ' . $record['correct_to'],
                300
            );

            return;
        }

        $this->successCounter++;
    }

    /**
     * @param int $rowNum
     * @param int $linkIdToDelete
     * @return bool
     */
    private function deleteLink(int $rowNum, int $linkIdToDelete): bool
    {
        try {
            $this->linkRepository->deleteById($linkIdToDelete);
        } catch (Exception $exception) {
            $this->createReportEntry(
                $rowNum,
                'Could not delete link with ID of ' . $linkIdToDelete,
                200
            );

            return false;
        }

        return true;
    }

    /**
     * @param int $rowNum
     * @param int $customerId
     * @return bool
     */
    private function removeCustomer(int $rowNum, int $customerId): bool
    {
        try {
            $this->customerRepository->deleteById($customerId);
        } catch (\Exception $exception) {
            $this->createReportEntry(
                $rowNum,
                'Could not customer with ID of ' . $customerId,
                200
            );

            return false;
        }

        return true;
    }

    private function createReportEntry($rowNum, $message, $categoryCode)
    {
        $category = self::REPORT_CATEGORIES[$categoryCode];
        $this->reportEntries[$category][$rowNum] = sprintf(
            'Row #%d: %s',
            $rowNum,
            $message
        );
    }

    /**
     * @return bool
     * @throws FileSystemException
     */
    private function generateReport(): bool
    {
        if (!empty($this->reportEntries)) {
            $content = sprintf(
                'Successfully Processed %d out of %d Records.%s',
                $this->successCounter,
                $this->totalRowsCount,
                PHP_EOL
            );
            $implodeSeparator = PHP_EOL . '  ';
            $varDirWrite = $this->filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
            extract($this->reportEntries);

            if (!empty($skipped)) {
                ksort($skipped);
                $content .= PHP_EOL . 'Skipped Rows:' . PHP_EOL;
                $content .= '  ' . implode($implodeSeparator, $skipped) . PHP_EOL;
            }

            if (!empty($unable_to_delete)) {
                ksort($unable_to_delete);
                $content .= PHP_EOL . 'Rows where the incorrect links should have been deleted but were unable to:' . PHP_EOL;
                $content .= '  ' . implode($implodeSeparator, $unable_to_delete) . PHP_EOL;
            }

            if (!empty($unable_to_add)) {
                ksort($unable_to_add);
                $content .= PHP_EOL . 'Rows where a correct link should have been added but was unable to:' . PHP_EOL;
                $content .= '  ' . implode($implodeSeparator, $unable_to_add) . PHP_EOL;
            }

            if (!empty($other)) {
                $content .= PHP_EOL . 'Other report entries:' . PHP_EOL;
                $content .= '  ' . implode($implodeSeparator, $other) . PHP_EOL;
            }

            $varDirWrite->writeFile(self::ERROR_REPORTING_FILE, $content);

            return true;
        }

        return false;
    }
}
