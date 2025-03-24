<?php

class eclEngine_modules
{
    private array $modules = [];
    private eclEngine_page $page;

    public function __construct(eclEngine_page $page)
    {
        $this->page = $page;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->modules)) {
            return $this->modules[$name];
        } else if (substr($name, 0, 5) === 'form_') {
            $class = "eclMod_" . $name;
            $this->modules[$name] = new $class($this->page);
            return $this->modules[$name];
        } else {
            $class = "eclMod_mod" . ucfirst($name);
            $this->modules[$name] = new $class($this->page);
            return $this->modules[$name];
        }
    }

    public function __set($name, $module)
    {
        global $store;
        if (is_string($module)) {
            $data = $store->staticContent->open($module);
            if (isset($data['flags']['module']) and preg_match('/^[a-z][a-zA-Z0-9_]*$/', $data['flags']['module'])) {
                $className = 'eclMod_' . $data['flags']['module'];
                $this->modules[$name] = new $className($this->page);
                $this->modules[$name]->data = $data;
            }
        } else {
            $this->modules[$name] = $module;
        }
    }

}
