<?php
namespace Rysun\DataTransfer\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Rysun\DataTransfer\Cron\ProductSepcificationImpCron;

class SpecificationImp extends Command
{
    /**
     * Rysun Cron  product specification attribute import
     */
    private $specificationCronImp;


    public function __construct(ProductSepcificationImpCron $specificationCronImp)
    {
        $this->specificationCronImp = $specificationCronImp;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
   protected function configure()
   {
       $this->setName('product:import:specification');
       $this->setDescription('Import Products specification');

       parent::configure();
   }

   /**
     * {@inheritdoc}
     */
   protected function execute(InputInterface $input, OutputInterface $output)
   {

        $this->specificationCronImp->execute();
        $output->writeln("Product Specification imported!");

       return 0;
   }
}