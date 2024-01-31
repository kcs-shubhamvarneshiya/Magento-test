<?php

namespace Rysun\ExcludeArch\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Rysun\ExcludeArch\Cron\ExcludeCron;

class ExcludeArch extends Command
{
    /**
     * Rysun Cron exclude arch categories products
     */
    private $excludeCron;

    /**
     * ExcludeArch constructor
     *
     * @param ExcludeCron $excludeCron
     */
    public function __construct(ExcludeCron $excludeCron)
    {
        $this->excludeCron = $excludeCron;
        parent::__construct();
    }

    /**
     * Retrieve Command
    */
    protected function configure()
    {
       $this->setName('exclude:architecture');
       $this->setDescription('Exclude architecture from sitemap and add noindex nofollow');
       parent::configure();
    }

    /**
     * Execute the exclude process
     *
     * @param $input
     * @param $output
    */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->excludeCron->execute();
        $output->writeln("Execute the exclude process");
        return 0;
    }
}
