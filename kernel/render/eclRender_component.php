<?php

class eclRender_component
{
    public eclMod $module;
    public array $slot;
    public array $scopes = [];

    public function __construct(eclMod $module, array $slot = [])
    {
        $this->module = $module;
        $this->slot = $slot;
    }

    public function getProperty(string $path, bool $returnString = false)
    {
        $current = $this->module;
        $parts = explode('.', $path);
        $name = $parts[0];
        if (!preg_match('/^[a-zA-Z][a-zA-Z0-9_]*$/', $name))
            return '';
        foreach ($this->scopes as $scope) {
            if (isset($scope[$name])) {
                $current = $scope[$name];
                array_shift($parts);
                break;
            }
        }
            while($parts) {
                $name = array_shift($parts);
            if (!preg_match('/^[a-zA-Z][a-zA-Z0-9_]*$/', $name))
                return '';
            if (preg_match('/^[A-Z][a-zA-Z0-9_]*$/', $name)) {
                $scopeName = 'eclScope_' . strtolower($name[0]) . substr($name, 1);
                $current = $scopeName::getScope($this->module->page, $current, array_shift($parts) ?? '');
                continue;
            }
            if (is_object($current) and get_class($current) === "eclEngine_child") {
                if ($name === 'children')
                    $current = $current->children;
                elseif (isset($current->data[$name]))
                    $current = $current->data[$name];
                elseif (isset($current->data['text'][$name]))
                    $current = $current->data['text'][$name];
                elseif (isset($current->data['flags'][$name]))
                    $current = $current->data['flags'][$name];
                else
                    return '';
            } elseif (is_array($current) and isset($current[$name])) {
                $current = $current[$name];
            } elseif (isset($current->$name)) {
                $current = $current->$name;
            } elseif (is_callable([$current, $name])) {
                $current = $current->$name();
            } else {
                return '';
            }
        }
        if ($returnString) {
            if (is_string($current))
                return $current;
            elseif (is_numeric($current))
                return strval($current);
            else if ($current === true)
                return 'true';
            else if ($current === false)
                return 'false';
            else
                return '';
        }
        return $current;
    }

}
