<?php

class eclEngine_components
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
        } else if (substr($name, 0, 5) === 'form_') {
            $class = "eclCom_" . $name;
            $this->components[$name] = new $class($this->page);
            return $this->components[$name];
        } else {
            $class = "eclCom_com" . ucfirst($name);
            $this->components[$name] = new $class($this->page);
            return $this->components[$name];
        }
    }

    public function __set($name, $component)
    {
        global $store;
        if (is_string($component)) {
            $data = $store->staticContent->open($component);
            if (isset($data['flags']['component']) and preg_match('/^[a-z][a-zA-Z0-9_]*$/', $data['flags']['component'])) {
                $className = 'eclCom_' . $data['flags']['component'];
                $this->components[$name] = new $className($this->page);
                $this->components[$name]->data = $data;
            }
        } else {
            $this->components[$name] = $component;
        }
    }

}
