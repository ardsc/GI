<?php

namespace Gi\Foundation;

use Exception;
use RuntimeException;

abstract class Facade
{

    protected static $app;

    public static function getPackageName()
    {
        throw new RuntimeException('Unable to initialzie package object');
    }

    public static function resolveBinding($name)
    {
        self::$app = app('app');
        return self::$app->resolveBinding($name);
    }

    public static function __callStatic($name, $arguments)
    {
        $instance = self::resolveBinding(self::getPackageName());

        if (! is_object($instance)) {
            throw new Exception('Package is not a concrete object');
        }

        return call_user_func_array([$instance, $name], $arguments);
    }
}