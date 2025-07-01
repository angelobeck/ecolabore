<?php

class eclStore_moduleTemplate
{
    protected $cache = [];
    protected $file;

    public function __construct()
    {
        if (PACK_ENABLED) {
            $this->file = fopen(PACK_FILE, 'rb');
        }
    }

    public function open(string $name): string
    {
        if (!isset($this->cache[$name])) {
            if (PACK_ENABLED)
                $this->cache[$name] = $this->getPackedContent($name);
            else
                $this->cache[$name] = $this->getFileContent($name);
        }
        return $this->cache[$name];
    }

    public function getPackedContent(string $name): string
    {
        global $staticTemplates;
        if (!isset($staticTemplates[$name]))
            return '';

        [$offset, $length] = $staticTemplates[$name];
        fseek($this->file, PACK_COMPILER_HALT_OFFSET + $offset);
        $fileSlice = fread($this->file, $length);
        return eclIo_convert::databaseUnescapeString($fileSlice);
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
