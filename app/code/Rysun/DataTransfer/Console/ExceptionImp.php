<?php
namespace Rysun\DataTransfer\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Rysun\DataTransfer\Cron\ProductExceptionImpCron;

class ExceptionImp extends Command
{
    /**
     * Rysun Cron Trim product import
     */
    private $exceptionImp;


    public function __construct(ProductExceptionImpCron $exceptionImp)
    {
        $this->exceptionImp = $exceptionImp;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
   protected function configure()
   {
       $this->setName('product:exception:import');
       $this->setDescription('Product Exception Import');

       parent::configure();
   }

   /**
     * {@inheritdoc}
     */
   protected function execute(InputInterface $input, OutputInterface $output)
   {

      $this->exceptionImp->execute();
      $output->writeln(" Product Exception Imported! ");

       return 0;
   }
}
