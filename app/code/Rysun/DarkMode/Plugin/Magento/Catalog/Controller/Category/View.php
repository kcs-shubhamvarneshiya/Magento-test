<?php

declare(strict_types=1);

namespace Rysun\DarkMode\Plugin\Magento\Catalog\Controller\Category;

use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\App\RequestInterface;

class View
{
    protected $registry;
    protected $forwardFactory;
    protected $request;

    /**
    * @var \Magento\Framework\App\Config\ScopeConfigInterface
    */
    protected $scopeConfig;

    const XML_PATH_DARK_MODE_STATUS = 'darkmode/general/enable';

    const REQUEST_PARAM_DARKMODE = 'darkmode';
    
    public function __construct(
        \Magento\Framework\Registry $registry,
        ForwardFactory $forwardFactory,
        RequestInterface $request,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->registry = $registry;
        $this->forwardFactory = $forwardFactory;
        $this->request = $request;
        $this->scopeConfig = $scopeConfig;
    }

    public function beforeExecute(
        \Magento\Catalog\Controller\Category\View $subject
    ) {
        // Your code before the original method execution goes here
    }

    /**
     * Retrieve response object
     *
     * @return \Magento\Framework\App\ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }

    public function afterExecute(
        \Magento\Catalog\Controller\Category\View $subject,
        $result
    ) {
        // Check if the result is an instance of \Magento\Framework\View\Result\Page
        if ($result instanceof \Magento\Framework\View\Result\Page) {
            // Get the current category from the registry
            $currentCategory = $this->registry->registry('current_category');
            if ($currentCategory) {
                $categoryId = $currentCategory->getId();
                $categoryName = $currentCategory->getName();
                if ($currentCategory->getIsArchitechData()) {
                    $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
                    $darkModeSystemConfig = $this->scopeConfig->getValue(self::XML_PATH_DARK_MODE_STATUS, $storeScope);
                    if ($darkModeSystemConfig) {
                        $paramValue = $this->request->getParam(self::REQUEST_PARAM_DARKMODE);
                        if (!isset($paramValue) || $paramValue != 'Yes') {
                            //print_r($currentCategory->getData());
                            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                            $resultFactory = $objectManager->create(\Magento\Framework\Controller\ResultFactory::class);
                            $resultRedirect = $resultFactory->create(
                                \Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT
                            );
                            $result = $resultRedirect->setPath('restricted');
                            return $result;
                        }
                    }      
                }
            }
        }
        return $result;
    }
}

