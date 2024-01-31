<?php

namespace Capgemini\UrapidCron\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PriceImport extends Command
{
    /**
     * @var \Unirgy\RapidFlow\Helper\Data
     */
    protected $helper;

    public function __construct(
        \Unirgy\RapidFlow\Helper\Data $helper,
        string $name = null
    ) {
        parent::__construct($name);
        $this->helper = $helper;
    }
    /**
     * Initialization of the command.
     */
    protected function configure()
    {
        $this->setName('urf:import:price');
        $this->setDescription('Import');
        parent::configure();
    }

    /**
     * CLI command description.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->helper->run('VC Price List Import', true, ['keep_session'=>true]);
    }
}
