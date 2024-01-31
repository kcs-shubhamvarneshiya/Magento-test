<?php

namespace Lyonscg\SalesPad\Cron;

use Lyonscg\SalesPad\Model\Api;
use Lyonscg\SalesPad\Model\Api\Logger;
use Lyonscg\SalesPad\Model\ResourceModel\Session;
use Lyonscg\SalesPad\Model\ResourceModel\Session\Collection as SessionCollection;
use Lyonscg\SalesPad\Model\ResourceModel\Session\CollectionFactory as SessionCollectionFactory;

class ExpiredSessionsCleanup
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var Api
     */
    private $api;

    /**
     * @var SessionCollectionFactory
     */
    private $sessionCollectionFactory;

    /**
     * @var Session
     */
    private $sessionResource;

    /**
     * @param Api $api
     * @param SessionCollectionFactory $sessionCollectionFactory
     * @param Session $sessionResource
     * @param Logger $logger
     */
    public function __construct(Api $api, SessionCollectionFactory $sessionCollectionFactory, Session $sessionResource, Logger $logger)
    {
        $this->logger = $logger;
        $this->api = $api;
        $this->sessionCollectionFactory = $sessionCollectionFactory;
        $this->sessionResource = $sessionResource;
    }

    public function execute()
    {
        /** @var SessionCollection $sessionCollection */
        $sessionCollection = $this->sessionCollectionFactory->create();
        $sessionCollection->addActiveFilter()->setOrder('id', 'asc');
        $count = $sessionCollection->getSize();

        if($count === 0) {
            $this->api->setSessionId(false);
        } elseif ($count === 1) {

            return;
        }

        $sessionCollection->setPageSize($count - 1)->setCurPage(1);

        foreach ($sessionCollection as $item) {

            if (!$item->getId()) {

                continue;
            }

            try {
                if (!$this->api->ping($item->getSessionId())) {
                    $item->setActive(0);
                    $item->setStamp(date('Y-m-d H:i:s'));
                    $this->sessionResource->save($item);
                }
            } catch (\Exception $exception) {
                $this->logger->debug('Lyonscg\SalesPad\Cron\ExpiredSessionsCleanup: Session with id of ' . $item->getId() . ' has not been saved while deactivating');
            }
        }
    }

}
