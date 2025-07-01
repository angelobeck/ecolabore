<?php

class eclEngine_endpoints
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
        } else {
            $class = "eclEndpoint_" . $name;
            $this->modules[$name] = new $class($this->page, $name);
            return $this->modules[$name];
        }
    }

    public function __set($name, $module)
    {
        global $store;
        if (is_string($module)) {
            $data = $store->staticContent->open($module);
            if (isset($data['flags']['endpoint']) and preg_match('/^[a-z][a-zA-Z0-9_]*$/', $data['flags']['endpoint'])) {
                $className = 'eclEndpoint_' . $data['flags']['endpoint'];
                $this->modules[$name] = new $className($this->page, $name);
                $this->modules[$name]->data = $data;
            }
        } else {
            $this->modules[$name] = $module;
        }
    }

}
