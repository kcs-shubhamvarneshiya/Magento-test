<?php
namespace Rysun\DataTransfer\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Rysun\DataTransfer\Cron\ProductCollectionImpCron;

class CollectionImp extends Command
{
    /**
     * Rysun Cron  product collection import
     */
    private $collectionProdImp;


    public function __construct(ProductCollectionImpCron $collectionProdImp)
    {
        $this->collectionProdImp = $collectionProdImp;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
   protected function configure()
   {
       $this->setName('product:import:collection');
       $this->setDescription('Import Products Collection');

       parent::configure();
   }

   /**
     * {@inheritdoc}
     */
   protected function execute(InputInterface $input, OutputInterface $output)
   {

        $this->collectionProdImp->execute();
        $output->writeln("Category collection imported!");
       return 0;
   }
}
