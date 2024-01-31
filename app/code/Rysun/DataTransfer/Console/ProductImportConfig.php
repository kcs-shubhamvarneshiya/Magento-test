<?php
namespace Rysun\DataTransfer\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Rysun\DataTransfer\Cron\ProductConfigImp;

class ProductImportConfig extends Command
{
    /**
     * Rysun Product config import
     */
    private $productConfigImp;


    public function __construct(ProductConfigImp $productConfigImp)
    {
        $this->productConfigImp = $productConfigImp;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
   protected function configure()
   {
       $this->setName('product:import:configurable');
       $this->setDescription('Import Configurable Products');

       parent::configure();
   }

   /**
     * {@inheritdoc}
     */
   protected function execute(InputInterface $input, OutputInterface $output)
   {

      $this->productConfigImp->execute();
      $output->writeln("Configurable Product Imported!");

       return 0;
   }
}
