<?php

namespace Lyonscg\SalesPad\Console\Command;

use Lyonscg\SalesPad\Model\Api as BaseApi;
use Magento\Framework\App\State;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateDocumentation extends Command
{
    const COMMAND_NAME = 'salespad:doc:generate';

    const DEST_DIR = 'dest_dir';

    /**
     * @var State
     */
    private $state;

    /**
     * @var BaseApi
     */
    private $api;

    /**
     * @var \Magento\Framework\Filesystem\Io\File
     */
    private $file;

    /**
     * GenerateDocumentation constructor.
     * @param State $state
     * @param BaseApi $api
     * @param \Magento\Framework\Filesystem\Io\File $file
     */
    public function __construct(
        State $state,
        BaseApi $api,
        \Magento\Framework\Filesystem\Io\File $file
    ) {
        parent::__construct();
        $this->state = $state;
        $this->api = $api;
        $this->file = $file;
    }

    protected function configure()
    {
        $this->setName(self::COMMAND_NAME)
            ->setDefinition([
                new InputArgument(
                    self::DEST_DIR,
                    InputArgument::REQUIRED,
                    'Destination Directory'
                )
            ]);

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $destDir = $input->getArgument(self::DEST_DIR);

        $sessionId = $this->api->getSessionId();
        $output->writeln("Got session id $sessionId");

        $this->file->open([
            'path' => $destDir
        ]);
        foreach ($this->getApiList() as $api) {
            $action = '/Help/Api/' . $api;
            $output->write("Getting $api... ");
            $fileName = 'api-' . $api . '.html';
            $result = $this->api->callApi($action, $this->api::GET);
            $html = $result->getBody();

            if ($this->file->write($fileName, $html)) {
                $output->writeln("<info>done.</info>");
            } else {
                $output->writeln("<error>failed to write file.</error>");
            }
        }
        $this->file->close();
    }

    /**
     * @return string[]
     */
    protected function getApiList()
    {
        return [
            'PUT-api-CustomerAddr_Customer_Num-Address_Code',
        ];
        // see the API-DetailHelpPage.pdf to get the help api
        // exclude the /Help/Api/ portion of the url for entries below
        /*
        return [
            'GET-api-Customer',
            'GET-api-Customer-Customer_Num',
            'GET-api-Customer-Customer_Num-SalesDocuments',
            'POST-api-Customer',
            'PUT-api-Customer-Customer_Num',
            'POST-api-Customer-CustomerAndAddress',
            'GET-api-CustomerAddr',
            'GET-api-CustomerAddr-Customer_Num-Address_Code',
            'POST-api-CustomerAddr',
            'PUT-api-CustomerAddr_Customer_Num-Address_Code',
            'DELETE-api-CustomerAddr-Customer_Num-Address_Code',
            'GET-api-CustomerAddr-Customer_Num-AddressCode',
            'GET-api-SalesDocument',
            'GET-api-SalesDocument-Sales_Doc_Type-Sales_Doc_Num',
            'PUT-api-SalesDocument-Sales_Doc_Type-Sales_Doc_Num',
            'DELETE-api-SalesDocument-Sales_Doc_Type-Sales_Doc_Num',
            'POST-api-SalesDocument',
            'POST-api-SalesDocument-WithLines',
            'PUT-api-SalesDocument-Sales_Doc_Type-Sales_Doc_Num-UpdateLineItems',
            'DELETE-api-SalesDocument-Sales_Doc_Type-Sales_Doc_Num-DeleteLineItems',
            'PUT-api-SalesDocument-Sales_Doc_Type-Sales_Doc_Num-Address_Code-ShippingAddress',
            'GET-api-SalesDocumentSearch',
            'GET-api-SalesDocumentSearch-Sales_Doc_Type-Sales_Doc_Num',
            'GET-api-Session-Ping',
            'GET-api-Session-ActiveUsers',
            'GET-api-Session',
            'DELETE-api-Session',
        ];
        */
    }
}
