<?php

namespace Capgemini\DataLayer\Controller\Ajax;

use Magento\Framework\App\Action\Context;
use Capgemini\DataLayer\Helper\Data as ModuleHelper;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\SerializerInterface;

class CustomerData extends \Magento\Framework\App\Action\Action
{
    /**
     * @var ModuleHelper
     */
    private $moduleHelper;
    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;
    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @param Context $context
     * @param ModuleHelper $moduleHelper
     * @param JsonFactory $resultJsonFactory
     * @param SerializerInterface $serializer
     */
    public function __construct(
        Context $context,
        ModuleHelper $moduleHelper,
        JsonFactory $resultJsonFactory,
        SerializerInterface $serializer
    ) {
        parent::__construct($context);

        $this->moduleHelper = $moduleHelper;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->serializer = $serializer;
    }

    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();
        try {
            $customerData = $this->moduleHelper->getCustomerData();
        } catch (NoSuchEntityException $e) {
            $customerData = [];
        }

        return $resultJson->setData($this->serializer->serialize($customerData));
    }
}
