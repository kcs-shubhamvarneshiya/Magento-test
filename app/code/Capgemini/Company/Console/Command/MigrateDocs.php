<?php

namespace Capgemini\Company\Console\Command;

use Capgemini\Company\Helper\Document;
use Capgemini\Company\Model\Company\DocumentRepository;
use Capgemini\Company\Model\ResourceModel\Company\Document\Contents;
use Exception;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Filesystem;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Zend_Db_Statement_Exception;

class MigrateDocs extends Command
{
    const MIGRATE_BATCH_SIZE = 1000;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Document
     */
    private $documentHelper;

    /**
     * @var
     */
    private $connection;

    /**
     * @var array
     */
    private $tables;

    /**
     * @var array
     */
    private $resultsAggregated = [
        'totalCount'           => 0,
        'successCount'         => 0,
        'failureCount'         => 0,
        'successfulIdsCount'   => 0,
        'repoTestSuccessCount' => 0
    ];

    /**
     * @var DocumentRepository
     */
    private $documentRepository;

    public function __construct(
        ResourceConnection $resourceConnection,
        Filesystem $filesystem,
        LoggerInterface $logger,
        Document $documentHelper,
        DocumentRepository $documentRepository,
        string $name = null
    ) {
        parent::__construct($name);
        $this->filesystem = $filesystem;
        $this->logger = $logger;
        $this->documentHelper = $documentHelper;
        $this->documentRepository = $documentRepository;
        $this->connection = $resourceConnection->getConnection();
        $this->tables = [
            'documents_table' => $resourceConnection->getTableName('company_documents'),
            'contents_table'  => $resourceConnection->getTableName('company_document_contents')
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) {
        $helper = $this->getHelper('question');
        $question = new Question(
            sprintf('Please enter the records batch size (default %s): ', self::MIGRATE_BATCH_SIZE),
            self::MIGRATE_BATCH_SIZE
        );
        $question->setValidator(function ($value) {

            return filter_var($value, FILTER_VALIDATE_INT);
        });
        $batchSizeAnswer = $helper->ask($input, $output, $question);

        if ($batchSizeAnswer === false) {
            $output->writeln('You need to enter an integer! Please run the command again with and provide a correct value.');

            return 1;
        }

        $output->writeln('---Starting Migration---');
        $this->logger->debug('migrate_trade_documents: Starting Migration.');
        try {
            $writeDirInstance = $this->filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
        } catch (Exception $e) {
            $this->logger->error('migrate_trade_documents: ' . $e->getMessage());
            $this->logger->debug('migrate_trade_documents: Migration Failed.');
            $output->writeln('Exception message:' . $e->getMessage());
            $output->writeln('---Migration Failed---');

            return $e->getCode();
        }

        $offset = 0;
        do {
            try {
                $batch = $this->getDataForMigration($batchSizeAnswer, $offset);
                $allRowsCount = $this->getTotalRecordsToMigrate();
            } catch (Exception $e) {
                $this->logger->error('migrate_trade_documents: ' . $e->getMessage());
                $this->logger->debug('migrate_trade_documents: Migration Failed.');
                $output->writeln('Exception message:' . $e->getMessage());
                $output->writeln('---Migration Failed---');

                return $e->getCode();
            }

            if (!$batchSize = count($batch)) {

                break;
            }

            $this->processBatch($batch, $writeDirInstance, $output);

            $recordsProcessed = $offset + $batchSize;
            $recordsRemaining = $allRowsCount - $recordsProcessed;

            if ($recordsRemaining > 0) {
                $output->write(PHP_EOL);
                $output->writeln(
                    sprintf('Altogether %d out of %d records have been processed. %d records remaining.',
                        $recordsProcessed,
                        $allRowsCount,
                        $recordsRemaining
                    )
                );

                if ($batchSize < $recordsRemaining) {
                    $batchCharacterizingAdjective = 'next';
                    $recordsToProcessNext = $batchSize;
                } else {
                    $batchCharacterizingAdjective = 'remaining';
                    $recordsToProcessNext = $recordsRemaining;
                }

                $question = new ConfirmationQuestion(
                    sprintf(
                        'Do You want to process the %s %d records?[y/N] (default: "Yes"): ',
                        $batchCharacterizingAdjective,
                        $recordsToProcessNext
                    ),
                    true
                );

                if (!$helper->ask($input, $output, $question)) {

                    break;
                }
            }

            $offset += $batchSizeAnswer;
        } while (true);
        $saveResultsString = sprintf(
            'Total documents: %1$d%4$sTotal successfully saved: %2$d%4$sTotal errors:%3$d',
            $this->resultsAggregated['totalCount'],
            $this->resultsAggregated['successCount'],
            $this->resultsAggregated['failureCount'],
            PHP_EOL
        );
        $repositoryTestString = sprintf(
            '---Total Repository Test Passed on %d out of %d Successfully Saved Documents---',
            $this->resultsAggregated['repoTestSuccessCount'],
            $this->resultsAggregated['successfulIdsCount']
        );

        $this->logger->debug('migrate_trade_documents:' . PHP_EOL . $saveResultsString);
        $this->logger->debug(
            sprintf(
                'migrate_trade_documents:%sTotal Repository Test Passed on %d out of %d Successfully Saved Documents',
                PHP_EOL,
                $this->resultsAggregated['repoTestSuccessCount'],
                $this->resultsAggregated['successfulIdsCount']
            )
        );
        $this->logger->debug('migrate_trade_documents: Migration Ended.');
        $output->write(PHP_EOL);
        $output->writeln('---Total Saving Results---');
        $output->writeln($saveResultsString);
        $output->write(PHP_EOL);
        $output->writeln($repositoryTestString);
        $output->write(PHP_EOL);
        $output->writeln('---Migration Ended---');

        return 0;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setDescription('Save all trade documents from company_document_contents table to file system.');
        parent::configure();
    }

    /**
     * @param $rows
     * @param $writeDirInstance
     * @param $output
     * @return void
     */
    private function processBatch($rows, $writeDirInstance, $output)
    {
        $successfulIds = [];
        $failureCount = 0;
        foreach ($rows as $row) {
            $timeParams = $this->getTimeParams($row['created_at']);
            extract($timeParams); // $timeDirName, $timeStamp appeared.
            $fileName = $this->documentHelper->buildSystemFileName($row['company_id'], $row['filename'], $timeDirName, $timeStamp);
            $contentsFilePath = sprintf(
                '/%s/%s',
                Contents::MAIN_SAVE_DIRECTORY,
                $fileName
            );
            try {
                $writeDirInstance->writeFile($contentsFilePath, $row['document_contents']);
                $this->connection->update(
                    $this->tables['documents_table'],
                    ['system_filename' => $fileName],
                    ['document_id=?' => $row['document_id']]
                );
                $output->write('.');
                $successfulIds[] = $row['document_id'];
            } catch (Exception $e) {
                $this->logger->error(sprintf(
                    'migrate_trade_documents: failed to create file or update DB on it for document %s due to %s',
                    $row['document_id'],
                    $e->getMessage()
                ));
                $output->write(PHP_EOL);
                $output->writeln('Document file for document ' . $row['document_id'] . 'has not been created or DB has not been updated on it');
                $output->writeln('Exception message:' . $e->getMessage());
                $failureCount++;
            }
        }
        $output->write(PHP_EOL);

        $documentIds = array_column($rows, 'document_id');
        try {
            $nonExistingDocIds = $this->checkReverseJoin($documentIds);
        } catch (Zend_Db_Statement_Exception $e) {
            $this->logger->error('migrate_trade_documents: could not verify if there exist contents for non-existing documents');
            $output->writeln('Could not verify if there exist contents for non-existing documents');
            $output->writeln('Exception message:' . $e->getMessage());
        }

        if (isset($nonExistingDocIds) && !empty($nonExistingDocIds)) {
            foreach ($nonExistingDocIds as $docId) {
                $this->logger->error('migrate_trade_documents: found content for a non-existing document, document ID: ' . $docId);
                $output->writeln('Found content for a non-existing document, document ID:' . $docId);
            }
            $failureCount += count($nonExistingDocIds);
        }

        $successCount = count($successfulIds);
        $totalCount = $successCount + $failureCount;
        $saveResultsString = sprintf(
            'Total documents: %1$d%4$sTotal successfully saved: %2$d%4$sTotal errors:%3$d',
            $totalCount,
            $successCount,
            $failureCount,
            PHP_EOL
        );
        $this->resultsAggregated['totalCount'] += $totalCount;
        $this->resultsAggregated['successCount'] += $successCount;
        $this->resultsAggregated['failureCount'] += $failureCount;
        $this->resultsAggregated['successfulIdsCount'] += count($successfulIds);

        $output->writeln('---Start Testing Repository---');
        foreach ($successfulIds as $successfulId) {
            try {
                $this->testOnRepository($successfulId);
                $output->write('.');
            } catch (Exception $e) {
                $output->write(PHP_EOL);
                $this->logger->error('migrate_trade_documents: Error when testing repository on '. $successfulId . ' document: ' . $e->getMessage());
                $output->writeln('Error when testing repository on '. $successfulId . ' document:' . $e->getMessage());
                $successCount--;
            }
        }
        $this->resultsAggregated['repoTestSuccessCount'] += $successCount;

        $output->write(PHP_EOL);
        $output->writeln('---Saving Results---');
        $output->writeln($saveResultsString);
        $output->write(PHP_EOL);
        $output->writeln('---Repository Test Passed on ' . $successCount . ' out of ' . count($successfulIds) . ' Successfully Saved Documents---');
    }

    /**
     * @param int $count
     * @param int $offset
     * @return array
     * @throws Zend_Db_Statement_Exception
     */
    private function getDataForMigration(int $count, int $offset): array
    {
        list($documentsTable, $contentsTable) = array_values($this->tables);
        $select = $this->connection->select()
            ->from(
                ['doc' => $documentsTable],
                [
                    'document_id' => 'doc.document_id',
                    'filename'    => 'doc.filename',
                    'company_id'  => 'doc.company_id',
                    'created_at'  => 'doc.created_at'
                ]
            )
            ->joinLeft(
                ['cont' => $contentsTable],
                'cont.document_id=doc.document_id',
                ['document_contents' => 'cont.document_contents']
            )->order('doc.created_at ASC')
            ->limit($count, $offset);
        $stmt = $this->connection->query($select);

        return $stmt->fetchAll();
    }

    /**
     * @return string
     * @throws Zend_Db_Statement_Exception
     */
    private function getTotalRecordsToMigrate(): string
    {
        list($documentsTable, $contentsTable) = array_values($this->tables);
        $select = $this->connection->select()
            ->from(
                ['doc' => $documentsTable],
                [
                    'count' => 'COUNT(*)'
                ]
            )
            ->joinLeft(
                ['cont' => $contentsTable],
                'cont.document_id=doc.document_id',
                ['document_contents' => 'cont.document_contents']
            );
        $stmt = $this->connection->query($select);

        return $stmt->fetchColumn();
    }

    /**
     * @param $timeMark
     * @return array
     */
    private function getTimeParams($timeMark): array
    {
        $timeStamp = strtotime($timeMark);
        $timeDirName = date('Y', $timeStamp) . date('m', $timeStamp);

        return [
            'timeDirName' => $timeDirName,
            'timeStamp'   => $timeStamp
        ];
    }

    /**
     * @param $documentIds
     * @return array
     * @throws Zend_Db_Statement_Exception
     */
    private function checkReverseJoin($documentIds): array
    {
        list($documentsTable, $contentsTable) = array_values($this->tables);
        $documentIds = implode(',', $documentIds);
        $select = $this->connection->select()
            ->from(['doc' => $documentsTable], [])
            ->joinRight(['cont' => $contentsTable],
                'cont.document_id=doc.document_id',
                ['document_id' => 'cont.document_id']
            )
            ->where('doc.document_id IS NULL')
            ->where('cont.document_id IN ' . '(' . $documentIds . ')');
        $stmt = $this->connection->query($select);
        $rows = $stmt->fetchAll();

        return array_column($rows, 'document_id');
    }

    /**
     * @param $successfulId
     * @return void
     * @throws NoSuchEntityException|LocalizedException
     */
    private function testOnRepository($successfulId)
    {
        $company = $this->documentRepository->getById($successfulId);
        $this->documentRepository->loadContents($company);
    }
}
