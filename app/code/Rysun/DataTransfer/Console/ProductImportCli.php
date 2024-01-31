<?php
namespace Rysun\DataTransfer\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Rysun\DataTransfer\Cron\ProductImport;

class ProductImportCli extends Command
{
    /**
     * Rysun Cron Trim product import
     */
    private $productImport;


    public function __construct(ProductImport $productImport)
    {
        $this->productImport = $productImport;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
   protected function configure()
   {
       $this->setName('product:import:simple');
       $this->setDescription('Import Simple Products');

       parent::configure();
   }

   /**
     * {@inheritdoc}
     */
   protected function execute(InputInterface $input, OutputInterface $output)
   {

      $this->productImport->execute();
      $output->writeln("Product Imported!");

       return 0;
   }
}
