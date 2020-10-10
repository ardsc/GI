<?php

namespace Gi\Foundation;

use Exception;

abstract class Facade
{

    /**
     * @var Application
     */
    protected static $app;

    /**
     * Override service name
     *
     * @throws Exception
     */
    protected static function setBindIdentity()
    {
        throw new Exception('Unable to initialize package object');
    }

    /**
     * Register service to application registry
     *
     * @param $name
     * @return array|mixed
     */
    public static function resolveBinding($name)
    {
        static::$app = app();
        static::$app->setRepository($name, get_called_class());

        return static::$app->resolveBinding($name);
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
        $identity = static::setBindIdentity();
        $instance = static::resolveBinding($identity);

        if (! is_object($instance)) {
            throw new Exception('Package ['.$identity.'] is not a concrete class');
        }

        switch (count($arguments)) {
            case 0:
                return $instance->$name();
            case 1:
                return $instance->$name($arguments[0]);
            case 2:
                return $instance->$name($arguments[0], $arguments[1]);
            case 3:
                return $instance->$name($arguments[0], $arguments[1], $arguments[2]);
            case 4:
                return $instance->$name($arguments[0], $arguments[1], $arguments[2], $arguments[3]);
            default:
                return call_user_func_array([$instance, $name], $arguments);
        }

    }
}