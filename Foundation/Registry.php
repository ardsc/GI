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
    protected static $repositoryObject = [];

    /**
     * Preload class
     *
     * @var string[]
     */
    protected static $repository = [
        'env' => 'Gi\Env',
        'config' => 'Gi\Config',
        'error.handler' => 'Gi\ErrorHandler',
        'request' => 'Gi\Request',
        'router' => 'Gi\Router'
    ];

    /**
     * Create or store class object in memory
     *
     * @param string $name
     * @return array|mixed
     */
    public function resolveBinding(string $name)
    {
        if ($this->hasBound($name)) {
            return $this->getRepositoryObject($name);
        }

        return $this->prepareBinding($name);
    }

    /**
     * Check if class has bound
     *
     * @param string $name
     * @return bool
     */
    protected function hasBound(string $name)
    {
        return isset($this->getAllRepositoryObject()[$name]);
    }

    /**
     * Get all the bound class
     *
     * @return array
     */
    public function getAllRepositoryObject()
    {
        return static::$repositoryObject;
    }

    /**
     * Get bound concrete class
     *
     * @param string $name
     * @return mixed
     */
    public function getRepositoryObject(string $name)
    {
        return static::$repositoryObject[$name];
    }

    /**
     * Bound namespace to repository object
     *
     * @param string $name
     * @return array|mixed
     */
    private function prepareBinding(string $name)
    {
        if (!$this->hasRegistered($name)) {
            throw new RuntimeException('Unable to find namespace for [ ' . $name . ' ] service');
        }

        $this->makeConcrete($name, $this->getRepository($name));
        return $this->getRepositoryObject($name);
    }

    /**
     * Check if namespace has registered
     *
     * @param string $name
     * @return bool
     */
    protected function hasRegistered(string $name)
    {
        return isset($this->getAllRepository()[$name]);
    }

    /**
     * Get all the registered namespace
     *
     * @return string[]
     */
    public function getAllRepository()
    {
        return static::$repository;
    }

    /**
     * Instantiate class name to concrete class
     *
     * @param string $name
     * @param string $namespace
     * @return void
     */
    private function makeConcrete(string $name, string $namespace)
    {
        if (!class_exists($namespace)) {
            throw new RuntimeException('Class ' . $namespace . ' is not exist');
        }

        $this->setRepositoryObject($name, new $namespace);
    }

    /**
     * Setter for repository object
     *
     * @param string $name
     * @param $value
     * @return Registry
     */
    protected function setRepositoryObject(string $name, $value)
    {
        static::$repositoryObject[$name] = $value;
        return $this;
    }

    /**
     * Get registered namespace
     *
     * @param string $name
     * @return array|mixed
     */
    public function getRepository(string $name)
    {
        return $name ? static::$repository[$name] : static::$repository;
    }

    /**
     * Store namespace/concrete class to repository
     *
     * @param string $name
     * @param string $value
     * @return Registry
     */
    public function setRepository(string $name, string $value)
    {
        if ($this->hasRegistered($name)) {
            throw new RuntimeException('Identity ' . $name . ' has registered');
        }

        static::$repository[$name] = $value;
        return $this;
    }

}