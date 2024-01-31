<?php

namespace Lyonscg\SalesPad\Model\Sync;

use Lyonscg\SalesPad\Api\Data\AbstractSyncInterface;
use Magento\Framework\Lock\LockManagerInterface;
use Magento\Framework\Model\AbstractModel;

abstract class AbstractSync extends AbstractModel implements AbstractSyncInterface
{
    /**
     * @var LockManagerInterface
     */
    protected $locker;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->getData(self::SYNC_ID);
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        return $this->setData(self::SYNC_ID, $id);
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * @return int
     */
    public function getSyncAttempts()
    {
        return $this->getData(self::SYNC_ATTEMPTS);
    }

    /**
     * @param int $syncAttempts
     * @return $this
     */
    public function setSyncAttempts($syncAttempts)
    {
        return $this->setData(self::SYNC_ATTEMPTS, $syncAttempts);
    }

    /**
     * @return string
     */
    public function getLastSyncAttemptAt()
    {
        return $this->getData(self::LAST_SYNC_ATTEMPT_AT);
    }

    /**
     * @param string $attemptAt
     * @return $this
     */
    public function setLastSyncAttemptAt($attemptAt)
    {
        return $this->setData(self::LAST_SYNC_ATTEMPT_AT, $attemptAt);
    }

    /**
     * @return string
     */
    public function getSyncAction()
    {
        return $this->getData(self::SYNC_ACTION);
    }

    /**
     * @param string $syncAction
     * @return AbstractSync
     */
    public function setSyncAction($syncAction)
    {
        if (!in_array($syncAction, self::VALID_SYNC_ACTIONS)) {
            throw new \InvalidArgumentException(sprintf('"%s" is not a valid sync_action', strval($syncAction)));
        }
        return $this->setData(self::SYNC_ACTION, $syncAction);
    }

    /**
     * Sync entity with SalesPad api
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function sync()
    {
        if ($this->canSync()) {
            $lockIdentifier = sprintf(
                'SALESPAD_SYNC_%s_%s',
                strtoupper($this->getSyncedEntityType()),
                $this->getSyncedEntityId()
            );
            if ($this->locker->lock($lockIdentifier, 0)) {
                try {
                    $result = $this
                        ->setStatus(self::STATUS_RUNNING)
                        ->runSync();
                    $this->updateStatus($result);
                } catch (\Exception $e) {
                    $this->updateStatus($e);
                    throw $e;
                } finally {
                    $this->locker->unlock($lockIdentifier);
                }

                return $result;
            }
        }

        return null;
    }

    /**
     * @return string
     */
    public function getFailures()
    {
        return $this->getData(self::FAILURES);
    }

    /**
     * @param array $failures
     * @return AbstractSync
     */
    public function setFailures(array $failures)
    {
        return $this->setData(self::FAILURES, implode("\n\n", $failures));
    }

    /**
     * @inheritDoc
     */
    public function getSyncedEntityType()
    {
        return static::SYNCED_ENTITY_TYPE;
    }

    /**
     * @inheritDoc
     */
    public function getSyncedEntityId()
    {
        return $this->getData(static::SYNCED_ENTITY_ID);
    }

    /**
     * @inheriDoc
     */
    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }

    /**
     * @inheriDoc
     */
    public function canSync()
    {
        return $this->getData(self::STATUS) === self::STATUS_PENDING || $this->getData(self::STATUS) === self::STATUS_ERROR;
    }

    /**
     * Sync entity with SalesPad api
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    abstract protected function runSync();

    /**
     * @param string $status
     * @return $this
     * @throws \Exception
     */
    private function setStatus(string $status): AbstractSync
    {
        if ($this->getData(self::STATUS) !== $status) {
            $this->setData(self::STATUS, $status);
            try {
                $this->save();
            } catch (\Exception $exception) {
                $this->_logger->error(sprintf(
                    'Can not change sync status of the sync entry (sync_id=%d) from %s to %s',
                    $this->getId(),
                    $this->getData(self::STATUS),
                    $status
                ));
                throw $exception;
            }
        }

        return $this;
    }

    /**
     * @param mixed $result
     * @return void
     * @throws \Exception
     */
    private function updateStatus($result)
    {
        if ($result instanceof \Exception) {
            $this->setStatus(self::STATUS_ERROR);
        } elseif (!$result) {
            $this->setStatus(self::STATUS_ERROR);
        } else {
            $this->setStatus(self::STATUS_SUCCESS);
        }
    }
}
