<?php
namespace Rysun\DataTransfer\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Rysun\DataTransfer\Cron\ProductPlatformImpCron;

class PlatformImp extends Command
{
    /**
     * Rysun Cron  product platform import
     */
    private $platformProdImp;


    public function __construct(ProductPlatformImpCron $platformProdImp)
    {
        $this->platformProdImp = $platformProdImp;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
   protected function configure()
   {
       $this->setName('product:import:platform');
       $this->setDescription('Import Products platforms');

       parent::configure();
   }

   /**
     * {@inheritdoc}
     */
   protected function execute(InputInterface $input, OutputInterface $output)
   {

        $this->platformProdImp->execute();
        $output->writeln(" Category platforms imported");
       return 0;
   }
}
