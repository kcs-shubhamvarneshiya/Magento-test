<?php
namespace Rysun\DataTransfer\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Rysun\DataTransfer\Cron\ProductSpecificationImpCron;


class SpecificationImp extends Command{


    private $specificationCronImp;


    public function __construct(ProductSpecificationImpCron $specificationCronImp)
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

?>
