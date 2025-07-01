<?php

class EclStore_staticContent
{
    protected $cache = [];
    protected $file;

    public function __construct()
    {
        if (PACK_ENABLED) {
            $this->file = fopen(PACK_FILE, 'rb');
        }
    }

    public function open(string $name): array
    {
        if (!isset($this->cache[$name])) {
            if (PACK_ENABLED)
                $control = $this->getPackedContent($name);
            else
                $control = $this->getContent($name);

            if (!is_array($control))
                $control = [];

            if (isset($control['children'])) {
                $values = array_values($control['children']);
                $control['children'] = [];
                $parts = explode('_', $name);
                $modulePrefix = join('_', array_slice($parts, 0, -1));
                foreach ($values as $value) {
                    if (is_string($value) and isset($value[0]) and $value[0] == '~')
                        $control['children'][] = $modulePrefix . '_' . substr($value, 1);
                    else
                        $control['children'][] = $value;
                }
            }
            $this->cache[$name] = $control;
        }
        return $this->cache[$name];
    }

    public function getPackedContent(string $name): mixed
    {
        global $staticContents;
        if (!isset($staticContents[$name]))
            return [];

        [$offset, $length] = $staticContents[$name];
        fseek($this->file, PACK_COMPILER_HALT_OFFSET + $offset);
        $fileSlice = fread($this->file, $length);
        return unserialize(eclIo_convert::databaseUnescapeString($fileSlice));
    }

    private function getContent(string $name): mixed
    {
        global $includePaths;
        $parts = explode('_', $name);
        $folder = implode('/', array_slice($parts, 0, -1));
        $sufix = end($parts);

        foreach ($includePaths as $base) {
            $path = $base . 'components/' . $folder . '/_staticContents/' . $sufix . '.json';
            if (is_file($path)) {
                $contents = file_get_contents($path);
                return eclIo_convert::json2array($contents, $path);
            }
        }
        return [];
    }

}
