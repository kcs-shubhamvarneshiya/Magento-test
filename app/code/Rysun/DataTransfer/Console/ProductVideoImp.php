<?php
namespace Rysun\DataTransfer\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Rysun\DataTransfer\Cron\ProductVideoImpCron;

class ProductVideoImp extends Command
{
    /**
     * Rysun Cron Product Video import
     */
    private $productVideoImp;


    public function __construct(ProductVideoImpCron $productVideoImp)
    {
        $this->productVideoImp = $productVideoImp;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
   protected function configure()
   {
       $this->setName('product:import:video');
       $this->setDescription('Product Video import');

       parent::configure();
   }

   /**
     * {@inheritdoc}
     */
   protected function execute(InputInterface $input, OutputInterface $output)
   {

        $this->productVideoImp->execute();
        $output->writeln("Product Video imported!");

       return 0;
   }
}
