<?php

namespace Gi;

use Gi\Foundation\Facade;
use Gi\Traits\ViewCompiler;

class View extends Facade
{

    use ViewCompiler;

    private
        $name = null,
        $data = [];

    public function path($path)
    {

        if (!is_null($path)) {

            $path1 = $path;
        }

        return $this;
    }

    public function name($name = null)
    {

        if (!is_null($name)) {

            $this->name = $name;
        }

        return $this;
    }

    public function data($key = null, $val = null)
    {

        if (is_null($key)) {
            return new Collection($this->data);
        }

        if (is_array($key)) {

            foreach ($key as $sub_key => $sub_val) {

                $this->data[$sub_key] = $sub_val;
            }

        } elseif (!is_null($val)) {

            $this->data[$key] = $val;
        }

        return $this;
    }

    protected static function setBindIdentity()
    {
        return 'view';
    }

    public function render()
    {

        extract($this->data);
        require $this->getCompiled($this->name);
    }
}