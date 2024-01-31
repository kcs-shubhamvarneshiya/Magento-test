<?php

namespace Lyonscg\SalesPad\Helper;

use Lyonscg\SalesPad\Api\Data\AbstractSyncInterface;

class SyncRegistry
{
    /**
     * @var array
     */
    private static $storage = [];

    /**
     * @param AbstractSyncInterface $sync
     * @return string
     */
    public static function register(AbstractSyncInterface $sync): string
    {
        $key = microtime();
        self::$storage[$key] = $sync;

        return $key;
    }

    /**
     * @param string $key
     * @return bool
     */
    public static function unregister(string $key): bool
    {
        if ($toReturn = isset(self::$storage[$key])) {
            unset(self::$storage[$key]);
        }

        return $toReturn;
    }

    /**
     * @return array|null
     */
    public static function getLastEntry(): ?array
    {
        if(end(self::$storage)) {

            return [
                'key'   => key(self::$storage),
                'value' => current(self::$storage)
            ];
        }

        return null;
    }

    /**
     * @return array|null
     */
    public static function getLastButOneEntry(): ?array
    {
        if(end(self::$storage)) {
            if (prev(self::$storage)) {

                return [
                    'key'   => key(self::$storage),
                    'value' => current(self::$storage)
                ];
            }
        }

        return null;
    }
}
