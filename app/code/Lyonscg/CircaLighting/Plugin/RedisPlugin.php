<?php

namespace Lyonscg\CircaLighting\Plugin;

class RedisPlugin
{
    /**
     * {@inheritdoc}
     */
    public function beforeLog($subject, $message, $level)
    {
        return [$message, $level];
    }
}
