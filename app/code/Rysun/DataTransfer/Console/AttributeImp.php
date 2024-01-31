<?php
namespace Rysun\DataTransfer\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Rysun\DataTransfer\Cron\AttributeImpCron;

class AttributeImp extends Command
{
    /**
     * Rysun Cron Trim product import
     */
    private $attributeImport;


    public function __construct(AttributeImpCron $attributeImport)
    {
        $this->attributeImport = $attributeImport;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
   protected function configure()
   {
       $this->setName('product:attribute:import');
       $this->setDescription('Import Product attribute');

       parent::configure();
   }

   /**
     * {@inheritdoc}
     */
   protected function execute(InputInterface $input, OutputInterface $output)
   {

      $this->attributeImport->execute();
      $output->writeln(" Attribute Sets, Attribute Group and Attribute and their options imported! ");

       return 0;
   }
}
