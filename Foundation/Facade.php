<?php

namespace Gi\Foundation;

use Exception;
use RuntimeException;

abstract class Facade
{

    protected static $app;

    /**
     * Override service name
     * @throws Exception
     */
    protected static function setBindIdentity()
    {
        throw new Exception('Unable to initialzie package object');
    }

    /**
     * Register service to application registry
     *
     * @param $name
     * @return array|mixed
     */
    public static function resolveBinding($name)
    {
        self::$app = app();
        self::$app->registerService($name, get_called_class());
        return self::$app->resolveBinding($name);
    }

    /**
     * Call Static magic method for dynamically access non static method
     *
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws Exception
     */
    public static function __callStatic($name, $arguments)
    {
        $instance = self::resolveBinding(static::setBindIdentity());

        if (! is_object($instance)) {
            throw new Exception('Package is not a concrete object');
        }

        return call_user_func_array([$instance, $name], $arguments);
    }
}