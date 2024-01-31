<?php
namespace Rysun\DataTransfer\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Rysun\DataTransfer\Cron\PricingImpCron;

class PriceImp extends Command
{
    /**
     * Rysun Cron Trim product import
     */
    private $priceImp;


    public function __construct(PricingImpCron $priceImp)
    {
        $this->priceImp = $priceImp;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
   protected function configure()
   {
       $this->setName('product:price:import');
       $this->setDescription('Product Price Import');

       parent::configure();
   }

   /**
     * {@inheritdoc}
     */
   protected function execute(InputInterface $input, OutputInterface $output)
   {

      $this->priceImp->execute();
      $output->writeln(" Product Price Import! ");
      return 0;
   }
}
