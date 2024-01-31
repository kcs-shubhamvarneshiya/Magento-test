<?php


namespace Capgemini\UrapidCron\Cron;


use Symfony\Component\Console\Input\InputOption;

class ProductDeltaUSImport
{
    /**
     * @var \Unirgy\RapidFlow\Helper\Data
     */
    protected $helper;

    public function __construct(
        \Unirgy\RapidFlow\Helper\Data $helper
    ) {
        $this->helper = $helper;
    }

    /**
     * @throws \Exception
     */
    public function execute(): void
    {
        $this->helper->run('PF-Updates-US', true, ['keep_session'=>true]);
    }

}
