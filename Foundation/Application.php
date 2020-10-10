<?php

namespace Gi\Foundation;

use Exception;
use Gi;


class Application extends Registry
{
    /**
     * @var string
     */
    protected $VERSION = '1.0.8';

    /**
     * Get access to application registry
     *
     * @param $name
     * @return mixed
     * @throws Exception
     */
    public static function resolve($name)
    {
        if (!isset(static::$repositoryObject[$name]) && $name) {
            throw new Exception('Object '.$name.' does\'nt exist');
        }

        return $name ? static::$repositoryObject[$name] : static::$repositoryObject['app'];
    }

    /**
     * Where the application begin
     *
     * @return Void
     */
    public function run()
    {
        $this->setRepositoryObject('app', $this);
        $this->settlePreload();
        $this->initializeStubs();
        $this->initializeLogger();

        date_default_timezone_set(config('app.timezone'));

        $url = $this->resolveBinding('request')::url();
        if ('/' == $url) $url = config('app.indexurl');

        $this->resolveBinding('router')->handle($url);
    }

    /**
     * Generate log file if not exist
     *
     * @return Void
     */
    protected function initializeLogger()
    {
        if (!file_exists(base_dir('error.log'))) {
            file_put_contents(base_dir('error.log'), '');
        }
    }

    /**
     * Bind preload class
     *
     * @return Void
     */
    protected function settlePreload()
    {
        $this->resolveBinding('env')::file(base_dir('.env'));
        $this->resolveBinding('config')::load(base_dir('config'));
        $this->resolveBinding('error.handler')::register();
    }

    /**
     * Prepare stub configuration
     *
     * @return Void
     */
    protected function initializeStubs()
    {
        $dir = dirname(__DIR__);
        $preloadStubs = [
            ['file' => '.htaccess', 'stubDir' => $dir . '/stubs/htacess.stub'],
            ['file' => 'index.php', 'stubDir' => $dir . '/stubs/index.stub'],
            ['file' => '.gitignore', 'stubDir' => $dir . '/stubs/gitignore.stub']
        ];

        foreach ($preloadStubs as $stubConf) {
            $this->settleStub($stubConf['file'], $stubConf['stubDir']);
        }

    }

    /**
     * Write stub
     *
     * @param $file
     * @param $stubDir
     */
    protected function settleStub($file, $stubDir)
    {
        if (!file_exists(base_dir($file))) {
            file_put_contents(base_dir($file), stub($stubDir));
        }
    }
}
