<?php
namespace Rysun\DataTransfer\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Rysun\DataTransfer\Cron\ImageImpCron;

class ImageImp extends Command
{
    /**
     * Rysun Cron product image import
     */
    private $imageImp;

    /** @var \Magento\Framework\App\State **/
    private $state;


    public function __construct(ImageImpCron $imageImp,\Magento\Framework\App\State $state)
    {
        $this->imageImp = $imageImp;
        $this->state = $state;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
   protected function configure()
   {
       $this->setName('product:image:import');
       $this->setDescription('Product Image Import');

       parent::configure();
   }

   /**
     * {@inheritdoc}
     */
   protected function execute(InputInterface $input, OutputInterface $output)
   {
      $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_GLOBAL);
      $this->imageImp->execute();
      $output->writeln(" Product Image Import! ");
      return 0;
   }
}
