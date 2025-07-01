<?php

class eclEngine_application
{
    public string $name;
    public string $applicationName;
    public bool|eclEngine_application $parent;

    public array $map = [];
    public array $data = [];
    public int $access = 0;
    public array $path;
    public bool $ignoreSubfolders = false;
    public int $domainId = 0;
    public int $userId = 0;

    private array $childrenByName = [];
    private array $allChildren = [];
    private bool $allChildrenIsLoaded = false;

    public function __construct(eclEngine_application|bool $parent = false, string $applicationName = 'root', string $name = '-root')
    {
        global $store;
        $this->parent = $parent;
        $this->applicationName = $applicationName;
        $this->name = $name;
        if ($parent) {
            $this->access = $parent->access;
            $this->path = [...$parent->path, $name];
            $this->domainId = $parent->domainId;
            $this->userId = $parent->userId;
        } else {
            $this->path = [];
        }

        $helper = 'eclApp_' . $applicationName;
        if (isset($helper::$access) && $helper::$access > $this->access) {
            $this->access = $helper::$access;
        }
        if (isset($helper::$map)) {
            $this->map = $helper::$map;
        }
        if (isset($helper::$content)) {
            $this->data = $store->staticContent->open($helper::$content);
        }

        $helper::constructorHelper($this);
    }

    public function child(string $name): eclEngine_application|bool
    {
        if (isset($this->childrenByName[$name])) {
            return $this->childrenByName[$name];
        }
        if ($this->allChildrenIsLoaded) {
            return false;
        }
        foreach ($this->map as $applicationName) {
            $helper = 'eclApp_' . $applicationName;
            if (isset($helper::$name)) {
                if ($helper::$name != $name) {
                    continue;
                }
            } else if (!$helper::isChild($this, $name)) {
                continue;
            }

            $this->childrenByName[$name] = new eclEngine_application($this, $applicationName, $name);
            return $this->childrenByName[$name];
        }
        return false;
    }

    public function children(): array
    {
        if ($this->allChildrenIsLoaded) {
            return $this->allChildren;
        }

        $this->allChildrenIsLoaded = true;
        foreach ($this->map as $applicationName) {
            $helper = 'eclApp_' . $applicationName;
            $names = [];
            if (isset($helper::$name)) {
                $names = [$helper::$name];
            } else {
                $names = $helper::childrenNames($this);
            }
            foreach ($names as $name) {
                if (isset($this->childrenByName[$name])) {
                    $child = $this->childrenByName[$name];
                } else {
                    $child = new eclEngine_application($this, $applicationName, $name);
                    $this->childrenByName[$name] = $child;
                }
                $this->allChildren[] = $child;
            }
        }

        return $this->allChildren;
    }

    public function reset()
    {
        $this->childrenByName = [];
        $this->allChildren = [];
        $this->allChildrenIsLoaded = false;
    }

}
