<?php

namespace Capgemini\TemporaryDebugger\Plugin\Amasty\Shopby;

use Amasty\Shopby\Plugin\Ajax\CategoryViewAjax as Subject;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\RequestInterface;
use Psr\Log\LoggerInterface;

class CategoryViewAjax
{
    private LoggerInterface $logger;
    private RequestInterface $request;

    public function __construct(LoggerInterface $logger, RequestInterface $request)
    {
        $this->logger = $logger;
        $this->request = $request;
    }

    public function aroundAfterExecute(Subject $subject, $proceed, Action $controller, $page)
    {
        try {
            return $proceed($controller, $page);
        } catch (\Throwable $e) {
            $requestDataString = 'REQUEST => ' . PHP_EOL . '    ' . print_r($this->request->getParams(), true) . PHP_EOL;
            $serverDataString = 'SERVER => ' . PHP_EOL . '    ' . print_r($_SERVER, true) . PHP_EOL;
            $this->logger->error(sprintf(
                '\Capgemini\TemporaryDebugger\Plugin\Amasty\Shopby\CategoryViewAjax: %s%s%s',
                PHP_EOL,
                $requestDataString,
                $serverDataString
            ));

            throw $e;
        }
    }
}
