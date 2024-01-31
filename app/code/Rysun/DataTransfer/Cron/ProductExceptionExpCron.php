<?php

namespace Rysun\DataTransfer\Cron;

use Psr\Log\LoggerInterface;

class ProductExceptionExpCron
{
    protected $logger;

    protected $fileFactory;
    protected $csvProcessor;
    protected $directoryList;

    public function __construct(
        LoggerInterface                                  $logger,
        \Kcs\Pacjson\Model\PacjsonFactory                $Pacjson,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\File\Csv                      $csvProcessor,
        \Magento\Framework\App\Filesystem\DirectoryList  $directoryList
    )
    {
        $this->logger = $logger;
        $this->Pacjson = $Pacjson;
        $this->fileFactory = $fileFactory;
        $this->csvProcessor = $csvProcessor;
        $this->directoryList = $directoryList;
    }

    /**
     * Write to system.log
     *
     * @return void
     */
    public function execute()
    {
        //$this->logger->info('Cron Works');

        $fileName = 'ProductExceptionExp.csv';
        $filePath = $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR)
            . "/urapidflow/export/" . $fileName;

        //$customer = $this->Pacjson->getCustomer();
        $personalData = $this->getPresonalData();
        //echo
        //print_r($personalData);exit;

        $this->csvProcessor
            ->setDelimiter(',')
            ->setEnclosure('"')
            ->saveData(
                $filePath,
                $personalData
            );

        return $this->fileFactory->create(
            $personalData,
            [
                'type' => "filename",
                'value' => $filePath,
                'rm' => true,
            ],
            \Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR,
            'application/octet-stream'
        );
    }

    protected function getPresonalData()
    {
        $result = [];
        $PacjsonData = $this->Pacjson->create();
        $collection = $PacjsonData->getCollection();
        $result[] = [
            //'csv_action',
            'entity_id',
            'pid',
            'pname',
            'attribute_combination',
            'option_combination_json',
            'status',
            'sql_serv_id',
            'sql_serv_prod_id'
        ];

        /*$result[] = [
            $PacjsonData['csv_action'],
            $PacjsonData['pid'],
            $PacjsonData['pname'],
            $PacjsonData['attribute_combination'],
            $PacjsonData['option_combination_json'],
            $PacjsonData['status']
        ];*/

        //$PacjsonDataId = 1;
        foreach ($collection as $PacjsonV) {
            $result[] = [
                //$PacjsonV['csv_action'],
                $PacjsonV['entity_id'],
                $PacjsonV['pid'],
                $PacjsonV['pname'],
                $PacjsonV['attribute_combination'],
                $PacjsonV['option_combination_json'],
                $PacjsonV['status'],
                $PacjsonV['sql_serv_id'],
                $PacjsonV['sql_serv_prod_id']
            ];
            //$PacjsonDataId++;
        }

        return $result;
    }
}
