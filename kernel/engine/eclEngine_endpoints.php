<?php

class eclEngine_endpoints
{
    private array $components = [];
    private eclEngine_page $page;

    public function __construct(eclEngine_page $page)
    {
        $this->page = $page;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->components)) {
            return $this->components[$name];
        } else {
            $class = "eclEndpoint_" . $name;
            $this->components[$name] = new $class($this->page, $name);
            return $this->components[$name];
        }
    }

    public function __set($name, $component)
    {
        global $store;
        if (is_string($component)) {
            $data = $store->staticContent->open($component);
            if (isset($data['flags']['endpoint']) and preg_match('/^[a-z][a-zA-Z0-9_]*$/', $data['flags']['endpoint'])) {
                $className = 'eclEndpoint_' . $data['flags']['endpoint'];
                $this->components[$name] = new $className($this->page, $name);
                $this->components[$name]->data = $data;
            }
        } else {
            $this->components[$name] = $component;
        }
    }

}
