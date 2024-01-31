<?php

namespace Capgemini\Payfabric\Helper;

class IgnoreValidatedRecaptcha
{
    private static bool $shouldIgnore = false;

    public static function setShouldIgnore(bool $bool): void
    {
        self::$shouldIgnore = $bool;
    }

    public static function getShouldIgnore()
    {
        return self::$shouldIgnore;
    }

    public static function wrap($object, $method, $arguments = [])
    {
        self::$shouldIgnore = true;
        $result = call_user_func_array([$object, $method], $arguments);
        self::$shouldIgnore = false;

        return $result;
    }
}
