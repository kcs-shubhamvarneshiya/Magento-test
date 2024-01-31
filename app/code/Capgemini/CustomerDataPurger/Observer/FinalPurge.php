<?php


namespace Capgemini\CustomerDataPurger\Observer;


use Capgemini\CustomerDataPurger\Model\AbstractPurger;
use Capgemini\CustomerDataPurger\Model\Config\Data;
use Magento\Framework\DataObject;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Message\ManagerInterface;

class FinalPurge implements ObserverInterface
{
    /**
     * @var Data
     */
    private $purgeListConfig;

    /**
     * @var AbstractPurger[]
     */
    private $purgingHandlersPool;

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    /**
     * FinalPurge constructor.
     * @param Data $purgeListConfig
     * @param AbstractPurger[] $purgingHandlersPool
     */
    public function __construct(Data $purgeListConfig, array $purgingHandlersPool, ManagerInterface $messageManager)
    {
        $this->purgeListConfig = $purgeListConfig;
        $this->purgingHandlersPool = $purgingHandlersPool;
        $this->messageManager = $messageManager;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $entity = $observer->getEvent()->getData('customer');
        $entityData = $entity->__toArray();
        $entityData = new DataObject($entityData);

        $purgeInstructions = $this->purgeListConfig->get('purge_instructions');
        foreach ($this->purgingHandlersPool as $instruction => $purger) {
            try {
                $purger->purge($entityData, $purgeInstructions[$instruction]);
            } catch (\Exception $exception) {
                $this->messageManager->addErrorMessage('Currently Your account can not be deleted. Please contact the store administration and show them this code: ' . $exception->getCode());
                $observer->getEvent()->getData('checktoken')->setData('flag', false);

                return;
            }
        }
    }
}
