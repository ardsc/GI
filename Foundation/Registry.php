<?php


namespace Gi\Foundation;

use RuntimeException;

abstract class Registry
{
    /**
     * Menampung class yang akan di load
     *
     * @var array
     */
    protected static $repository = [];

    /**
     * Preload class
     *
     * @var string[]
     */
    protected static $essentialServices = [
        'env'           => 'Gi\Env',
        'config'        => 'Gi\Config',
        'error.handler' => 'Gi\ErrorHandler',
        'request'       => 'Gi\Request',
        'router'        => 'Gi\Router'
    ];

    /**
     * Create or store class object in memory
     *
     * @param $name
     * @return array|mixed
     */
    public function resolveBinding($name)
    {
        $service = $this->prepareBinding($name);
        if (!is_object($service)) {
            $service = $this->makeConcrete($name, $service);
        }
        return $service;
    }

    /**
     * Check if class hasn't registered or get repo value
     *
     * @param $name
     * @return array|mixed
     */
    private function prepareBinding($name)
    {
        if (!$this->hasBound($name)) {
            throw new RuntimeException('Unable to find [ '.$name.' ] service');
        }

        return $this->getRepository($name);
    }

    /**
     * Check if class hasn't registered
     *
     * @param $name
     * @return bool
     */
    protected function hasBound($name)
    {
        return isset($this->getRepository()[$name]);
    }

    /**
     * Store namespace/concrete class to repository
     *
     * @param $name
     * @param $value
     */
    protected function setRepository($name, $value)
    {
        self::$repository[$name] = $value;
    }

    /**
     * Get namespace/concrete class from repository
     *
     * @param string $name
     * @return array|mixed
     */
    protected function getRepository($name = '')
    {
        $repo = array_merge(self::$essentialServices, self::$repository);
        return $name ? $repo[$name] : $repo;
    }

    /**
     * Instantiate class name to concrete class
     *
     * @param $name
     * @param $namespace
     * @return array|mixed
     */
    private function makeConcrete($name, $namespace)
    {
        $this->setRepository($name, new $namespace);
        return $this->getRepository($name);
    }

    /**
     * Register namespace to repository as an object
     *
     * @param $name
     * @param $namespace
     * @return $this
     */
    public function registerService($name, $namespace)
    {
        if (!class_exists($namespace)) {
            throw new RuntimeException('Class '.$namespace.' is not exist');
        }

        $this->setRepository($name, new $namespace);

        return $this->getRepository($name);
    }

}