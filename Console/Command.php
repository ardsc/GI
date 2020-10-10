<?php

namespace Gi\Console;

use Gi\Foundation\Application as ApplicationRunner;
use Symfony\Component\Console\Application;

class Command extends Application
{
    /**
     * Command constructor.
     * @param string $name
     * @param string $version
     */
    public function __construct($name = 'UNKNOWN', $version = 'UNKNOWN')
    {
        parent::__construct(ApplicationRunner::$NAME, ApplicationRunner::$VERSION);
    }

    /**
     * Register semua commad disini
     *
     * @throws \Exception
     */
    public function registerCommand()
    {
        $this->add(new MakeCommand('make:controller'));
        $this->run();
    }

    // nanti ditambah apa terserah

}