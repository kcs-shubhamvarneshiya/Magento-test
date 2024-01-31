<?php
namespace Rysun\DataTransfer\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Rysun\DataTransfer\Cron\AttributeRangeImpCron;

class AttributeRangeImp extends Command
{
    /**
     * Rysun Cron Trim product import
     */
    private $attributeRangeImp;


    public function __construct(AttributeRangeImpCron $attributeRangeImp)
    {
        $this->attributeRangeImp = $attributeRangeImp;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
   protected function configure()
   {
       $this->setName('product:attribute:range');
       $this->setDescription('Attribute Range import');

       parent::configure();
   }

   /**
     * {@inheritdoc}
     */
   protected function execute(InputInterface $input, OutputInterface $output)
   {

      $this->attributeRangeImp->execute();
       $output->writeln(" Attribute range created! ");
       return 0;
   }
}
