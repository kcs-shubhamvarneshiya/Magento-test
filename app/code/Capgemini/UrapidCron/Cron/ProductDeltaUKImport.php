<?php


namespace Capgemini\UrapidCron\Cron;


use Symfony\Component\Console\Input\InputOption;

class ProductDeltaUKImport
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
        $this->helper->run('PF-Updates-UK', true, ['keep_session'=>true]);
    }

}
