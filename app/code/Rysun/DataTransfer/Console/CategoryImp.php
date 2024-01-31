<?php
namespace Rysun\DataTransfer\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Rysun\DataTransfer\Cron\CategoryImpCron;

class CategoryImp extends Command
{
    /**
     * Rysun Cron category import
     */
    private $categoryImp;


    public function __construct(CategoryImpCron $categoryImp)
    {
        $this->categoryImp = $categoryImp;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
   protected function configure()
   {
       $this->setName('category:import');
       $this->setDescription('Import Category');

       parent::configure();
   }

   /**
     * {@inheritdoc}
     */
   protected function execute(InputInterface $input, OutputInterface $output)
   {

       $this->categoryImp->execute();
       $output->writeln(" Categories imported! ");
       return 0;
   }
}
