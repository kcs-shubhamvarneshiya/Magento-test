<?php
namespace Rysun\DataTransfer\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Rysun\DataTransfer\Cron\FeatureImpCron;

class FeatureImp extends Command
{
    /**
     * Rysun Cron Trim product import
     */
    private $featureImp;


    public function __construct(FeatureImpCron $featureImp)
    {
        $this->featureImp = $featureImp;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
   protected function configure()
   {
       $this->setName('product:feature:import');
       $this->setDescription('Product Feature Import');

       parent::configure();
   }

   /**
     * {@inheritdoc}
     */
   protected function execute(InputInterface $input, OutputInterface $output)
   {

      $this->featureImp->execute();
      $output->writeln(" Product Feature Imported! ");
       return 0;
   }
}
