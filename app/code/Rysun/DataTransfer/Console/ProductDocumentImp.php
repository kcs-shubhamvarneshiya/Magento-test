<?php
namespace Rysun\DataTransfer\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Rysun\DataTransfer\Cron\ProductDocumentImpCron;

class ProductDocumentImp extends Command
{
    /**
     * Rysun Cron product document import
     */
    private $productDocumentImp;


    public function __construct(ProductDocumentImpCron $productDocumentImp)
    {
        $this->productDocumentImp = $productDocumentImp;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
   protected function configure()
   {
       $this->setName('product:import:document');
       $this->setDescription('Import Products document!');

       parent::configure();
   }

   /**
     * {@inheritdoc}
     */
   protected function execute(InputInterface $input, OutputInterface $output)
   {

        $this->productDocumentImp->execute();
        $output->writeln("Products documents imported!");

       return 0;
   }
}
