<?php

namespace Capgemini\BloomreachApi\Controller\Correct;

use Capgemini\BloomreachApi\Helper\Data as ModuleHelper;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;

class Search implements HttpGetActionInterface
{
    const TYPE = 'search';

    /**
     * @var ResultFactory
     */
    private $resultFactory;
    /**
     * @var ModuleHelper
     */
    private $moduleHelper;

    public function __construct(ResultFactory $resultFactory, ModuleHelper $moduleHelper)
    {
        $this->moduleHelper = $moduleHelper;
        $this->resultFactory = $resultFactory;
    }

    public function execute()
    {
        $result = $this->moduleHelper->process(self::TYPE);
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        return $resultJson->setData($result);
    }
}
