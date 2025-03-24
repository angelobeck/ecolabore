<?php

class eclStore_moduleTemplate
{
    protected $cache = [];

    public function open(string $name): string
    {
        if (!isset($this->cache[$name])) {
            $this->cache[$name] = $this->getFileContent($name);
        }
        return $this->cache[$name];
    }

    private function getFileContent(string $name): string
    {
        global $includePaths;
        $parts = explode('_', $name);
        $partsCount = count($parts);
        foreach ($includePaths as $base) {
            for ($i = 1; $i <= $partsCount - 1; $i++) {
                $path = $base . 'components/' . implode('/', array_slice($parts, 1, $i)) . '/' . $name . '.static.html';
                if (is_file($path)) {
                    return file_get_contents($path);
                }

                $path = $base . 'components/' . implode('/', array_slice($parts, 1, $i)) . '/' . $name . '.html';
                if (is_file($path)) {
                    return file_get_contents($path);
                }
            }
        }
        return '';
    }

}
