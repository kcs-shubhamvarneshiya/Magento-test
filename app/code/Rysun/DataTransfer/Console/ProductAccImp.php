<?php
namespace Rysun\DataTransfer\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Rysun\DataTransfer\Cron\ProductAccessoryImp;

class ProductAccImp extends Command
{
    /**
     * Rysun Cron Product Accessories Link import
     */
    private $productAccesory;


    public function __construct(ProductAccessoryImp $productAccesory)
    {
        $this->productAccesory = $productAccesory;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
   protected function configure()
   {
       $this->setName('product:import:accessorylink');
       $this->setDescription('Import Products Accessory Link');

       parent::configure();
   }

   /**
     * {@inheritdoc}
     */
   protected function execute(InputInterface $input, OutputInterface $output)
   {

        $this->productAccesory->execute();
        $output->writeln("Accessories imported!");
       return 0;
   }
}
