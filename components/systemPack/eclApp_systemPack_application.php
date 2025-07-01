<?php

class eclApp_systemPack_application extends eclApp
{
    public static $name = 'packager';

    public static function pack(): string
    {
        $file = file_get_contents(PACK_FILE);
        $pos = strpos($file, '__PACKAGER_INSERTION_POINT__');
        $pos += strlen('__PACKAGER_INSERTION_POINT__');

        $header = substr($file, 0, $pos) . CRLF . CRLF;
        $footer = substr($file, $pos);
        $systemIsPacked = "define('PACK_ENABLED', true);" . CRLF
            . "define('PACK_TIME', '" . TIME . "');" . CRLF . CRLF;

        $scripts = self::getScripts();
        [$staticContentsIndex, $staticContents] = self::getStaticContents();
        [$staticTemplatesIndex, $staticTemplates] = self::getStaticTemplates(strlen($staticContents));
        $maps = self::getMaps();

        return $header . $systemIsPacked . $staticContentsIndex . $staticTemplatesIndex . $scripts . $maps . $footer . $staticContents . $staticTemplates;
    }

    private static function getScripts(): string
    {

        if (SERVER_TIME_LIMIT !== 0) {
            set_time_limit(60);
        }

        $paths = [
            [PATH_ENGINE . 'kernel/', []],
            [PATH_ENGINE . 'components/', []],
            [PATH_APPLICATION . 'components/', []]
        ];

        if (defined('PLUGINS')) {
            foreach (PLUGINS as $plugin) {
                $paths[] = [PATH_ROOT . $plugin . 'components/', []];
            }
        }

        $subpaths = [];

        $currentPath = '';
        $nextPath = '';
        $folders = [];

        $buffer = '';


        while (count($paths)) {
            [$currentPath, $folders] = array_shift($paths);
            if (substr($currentPath, -1) === '/') {
                $currentPath = substr($currentPath, 0, -1);
            }

            foreach (scandir($currentPath) as $fileName) {
                if ($fileName[0] == '.') {
                    continue;
                }

                $nextPath = $currentPath . '/' . $fileName;
                if (is_dir($nextPath)) {
                    $paths[] = [$nextPath, [...$folders, $fileName]];
                    continue;
                }

                list($className, $extension) = explode('.', $fileName, 2);

                if ($extension == 'php') {
                    $file = file_get_contents($nextPath);
                    if (substr($file, 0, 5) === '<?php')
                        $file = substr($file, 5);
                    $buffer .= trim($file) . CRLF . CRLF;
                    continue;
                }
            }
        }
        return $buffer;
    }

    private static function getStaticContents(): array
    {

        if (SERVER_TIME_LIMIT !== 0) {
            set_time_limit(60);
        }

        $paths = [
            [PATH_ENGINE . 'kernel/', []],
            [PATH_ENGINE . 'components/', []],
            [PATH_APPLICATION . 'components/', []]
        ];

        if (defined('PLUGINS')) {
            foreach (PLUGINS as $plugin) {
                $paths[] = [PATH_ROOT . $plugin . 'components/', []];
            }
        }

        $subpaths = [];

        $currentPath = '';
        $nextPath = '';
        $folders = [];

        $buffer = '';
        $positions = [];


        while (count($paths)) {
            [$currentPath, $folders] = array_shift($paths);
            if (substr($currentPath, -1) === '/') {
                $currentPath = substr($currentPath, 0, -1);
            }

            foreach (scandir($currentPath) as $fileName) {
                if ($fileName[0] == '.') {
                    continue;
                }

                $nextPath = $currentPath . '/' . $fileName;
                if (is_dir($nextPath)) {
                    $paths[] = [$nextPath, [...$folders, $fileName]];
                    continue;
                }

                list($className, $extension) = explode('.', $fileName, 2);

                if ($extension == 'json') {
                    $file = file_get_contents($nextPath);
                    $array = eclIo_convert::json2array($file);
                    $offset = strlen($buffer);
                    $serialized = eclIo_convert::databaseEscapeString(serialize($array));
                    $length = strlen($serialized);
                    $buffer .= $serialized;
                    $staticFolders = array_slice($folders, 0, -1);
                    $name = implode('_', [...$staticFolders, $className]);
                    $positions[$name] = [$offset, $length];
                    continue;
                }
            }
        }

        $staticContents = '$staticContents = [' . CRLF;
        foreach ($positions as $name => $values) {
            $staticContents .= "'" . $name . "' => [" . $values[0] . ', ' . $values[1] . '],' . CRLF;
        }
        $staticContents .= '];' . CRLF . CRLF;

        return [$staticContents, $buffer];
    }

    private static function getStaticTemplates(int $initialOffset): array
    {

        if (SERVER_TIME_LIMIT !== 0) {
            set_time_limit(60);
        }

        $paths = [
            [PATH_ENGINE . 'kernel/', []],
            [PATH_ENGINE . 'components/', []],
            [PATH_APPLICATION . 'components/', []]
        ];

        if (defined('PLUGINS')) {
            foreach (PLUGINS as $plugin) {
                $paths[] = [PATH_ROOT . $plugin . 'components/', []];
            }
        }

        $subpaths = [];

        $currentPath = '';
        $nextPath = '';
        $folders = [];

        $buffer = '';
        $positions = [];

        while (count($paths)) {
            [$currentPath, $folders] = array_shift($paths);
            if (substr($currentPath, -1) === '/') {
                $currentPath = substr($currentPath, 0, -1);
            }

            foreach (scandir($currentPath) as $fileName) {
                if ($fileName[0] == '.') {
                    continue;
                }

                $nextPath = $currentPath . '/' . $fileName;
                if (is_dir($nextPath)) {
                    $paths[] = [$nextPath, [...$folders, $fileName]];
                    continue;
                }

                list($className, $extension) = explode('.', $fileName, 2);

                if ($extension == 'html' or $extension == 'static.html') {
                    $file = eclIo_convert::databaseEscapeString(file_get_contents($nextPath));
                    $offset = $initialOffset + strlen($buffer);
                    $length = strlen($file);
                    $buffer .= $file;
                    $positions[$className] = [$offset, $length];
                    continue;
                }
            }
        }

        $staticTemplates = '$staticTemplates = [' . CRLF;
        foreach ($positions as $name => $values) {
            $staticTemplates .= "'" . $name . "' => [" . $values[0] . ', ' . $values[1] . '],' . CRLF;
        }
        $staticTemplates .= '];' . CRLF . CRLF;

        return [$staticTemplates, $buffer];
    }

    private static function getMaps(): string
    {
        $buffer = '';

        $paths = [
            PATH_ENGINE,
            PATH_APPLICATION
        ];

        if (defined('PLUGINS')) {
            foreach (PLUGINS as $plugin) {
                $paths[] = PATH_ROOT . $plugin;
            }
        }

        foreach ($paths as $path) {
            if (is_file($path . 'map.php')) {
                $file = file_get_contents($path . 'map.php');
                $buffer .= substr($file, 5);
            }
        }
        return $buffer;
    }

}
