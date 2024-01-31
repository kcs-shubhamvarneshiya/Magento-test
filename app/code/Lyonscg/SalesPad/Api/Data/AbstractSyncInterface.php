<?php

namespace Lyonscg\SalesPad\Api\Data;

interface AbstractSyncInterface
{
    const ACTION_SEND = 'send';
    const ACTION_DELETE = 'delete';
    const VALID_SYNC_ACTIONS = [
        self::ACTION_SEND,
        self::ACTION_DELETE,
    ];
    const STATUS_PENDING       = 'pending';
    const STATUS_RUNNING       = 'running';
    const STATUS_SUCCESS       = 'success';
    const STATUS_ERROR         = 'error';

    const SYNC_ID              = 'sync_id';
    const CREATED_AT           = 'created_at';
    const SYNC_ATTEMPTS        = 'sync_attempts';
    const LAST_SYNC_ATTEMPT_AT = 'last_sync_attempt_at';
    const SYNC_ACTION          = 'sync_action';
    const FAILURES             = 'failures';
    const STATUS               = 'status';

    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getCreatedAt();

    /**
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt);

    /**
     * @return int
     */
    public function getSyncAttempts();

    /**
     * @param int $syncAttempts
     * @return $this
     */
    public function setSyncAttempts($syncAttempts);

    /**
     * @return string
     */
    public function getLastSyncAttemptAt();

    /**
     * @param string $attemptAt
     * @return $this
     */
    public function setLastSyncAttemptAt($attemptAt);

    /**
     * @return string
     */
    public function getSyncAction();

    /**
     * @param string $syncAction
     * @return $this
     */
    public function setSyncAction($syncAction);

    /**
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function sync();

    /**
     * @return string
     */
    public function getFailures();

    /**
     * @param array $failures
     * @return $this
     */
    public function setFailures(array $failures);

    /**
     * @return string
     */
    public function getSyncedEntityType();

    /**
     * @return int
     */
    public function getSyncedEntityId();

    /**
     * @return string
     */
    public function getStatus();

    /**
     * @return bool
     */
    public function canSync();
}
