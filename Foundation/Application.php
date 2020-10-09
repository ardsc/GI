<?php

namespace Gi\Foundation;

use Gi;


class Application extends Registry
{
    /**
     * @var string
     */
    protected $VERSION = '1.0.0';

    public static function resolve($name)
    {
        $repo = array_merge(self::$repository, self::$essentialServices);
        if (isset($repo[$name])) {
            return $repo[$name];
        }
        return new self();
    }

    public function run()
    {
        $this->setRepository('app', $this);
        $this->initializeHelper();

        $this->initializeStubs();

        $this->resolveBinding('env')::file(__DIR__ . '/../.env');
        $this->resolveBinding('config')::load(__DIR__ . '/../config');
        $this->resolveBinding('error.handler')::register();

        date_default_timezone_set(config('app.timezone'));
        $url = $this->resolveBinding('request')::url();

        if ('/' == $url) $url = config('app.indexurl');

        $this->resolveBinding('router')->handle($url);
    }

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

    protected function settleStub($file, $stubDir)
    {
        if (!file_exists(base_dir($file))) {
            file_put_contents(base_dir($file), stub($stubDir));
        }
    }
}
