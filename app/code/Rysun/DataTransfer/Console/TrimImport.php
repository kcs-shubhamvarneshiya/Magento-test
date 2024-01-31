<?php
namespace Rysun\DataTransfer\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Rysun\DataTransfer\Cron\ProductTrimImpCron;

class TrimImport extends Command
{
    /**
     * Rysun Cron Trim product import
     */
    private $trimProductImp;


    public function __construct(ProductTrimImpCron $trimProductImp)
    {
        $this->trimProductImp = $trimProductImp;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
   protected function configure()
   {
       $this->setName('product:import:trim');
       $this->setDescription('Import Trim Products');

       parent::configure();
   }

   /**
     * {@inheritdoc}
     */
   protected function execute(InputInterface $input, OutputInterface $output)
   {

        $this->trimProductImp->execute();
        $output->writeln("Trim products imported!");

       return 0;
   }
}
