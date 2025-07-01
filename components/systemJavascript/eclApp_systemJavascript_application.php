<?php

class eclApp_systemJavascript_application extends eclApp
{
    public static $name = 'application.js';

    public static function view_main(eclEngine_page $page): void
    {
        $page->buffer = self::generate_script($page);
        $page->contentType = "text/javascript";
    }

    public static function generate_script(eclEngine_page $page, bool $generateHTML = false): string
    {
        global $applicationsPaths;

        if (SERVER_TIME_LIMIT !== 0) {
            set_time_limit(60);
        }

        $scripts = [];
        $staticContents = [];
        $templates = [];
        $registeredClasses = [];
        $classes = [];

        $paths = [
            [PATH_ENGINE . 'kernel/', []],
            [PATH_ENGINE . 'components/', []],
            [PATH_APPLICATION . 'components/', []]
        ];

        if (defined('PLUGINS')) {
            foreach (PLUGINS as $plugin) {
                $paths[] = [PATH_ROOT . $plugin . '/components/', []];
            }
        }

        $subpaths = [];

        $index = 0;
        $currentPath = '';
        $nextPath = '';
        $folders = [];

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
                    $subpaths[] = [$nextPath, [...$folders, $fileName]];
                    continue;
                }

                list($className, $extension) = explode('.', $fileName, 2);

                if ($extension == 'js') {
                    $classNameParts = explode('_', $className);
                    if (count($classNameParts) > 1) {
                        [$prefix, $sufix] = explode('_', $className, 2);
                        switch ($prefix) {
                            case 'eclEngine':
                            case 'eclRender':
                            case 'eclStore':
                                break;

                            default:
                                if (!isset($classes[$prefix]))
                                    $classes[$prefix] = [$sufix];
                                else
                                    $classes[$prefix][] = $sufix;
                        }
                    }

                    if ($generateHTML) {
                        $src = $page->protocol . '//' . SERVER_HOST . substr($nextPath, strlen(PATH_ROOT));
                        $scripts[] = '<script src="' . $src . '"></script>';
                    } else {
                        $scripts[] = trim(file_get_contents($nextPath));
                    }
                    continue;
                }

                if ($extension == 'html') {
                    $templates[$className] = file_get_contents($nextPath);
                    continue;
                }

                if ($extension == 'json') {
                    $currentFolder = array_pop($folders);
                    if ($currentFolder == '_staticContents') {
                        $staticContents[implode('_', $folders) . '_' . $className] = file_get_contents($nextPath);
                    }
                    array_push($folders, $currentFolder);
                    continue;
                }
            }

            if ($subpaths) {
                array_unshift($paths, ...$subpaths);
                $subpaths = [];
            }
        }

        if ($generateHTML) {
            $bufferScripts = implode(CRLF, $scripts);
        } else {
            $bufferScripts = implode(CRLF . CRLF, $scripts);
        }

        $bufferStaticContents = 'var staticContents = {' . CRLF
            . self::concatList($staticContents, '', '')
            . '}' . CRLF . CRLF;

        $bufferTemplates = 'var templates = {' . CRLF
            . self::concatList($templates, '`', '`')
            . '}' . CRLF . CRLF;

        $bufferRegisteredClasses = 'var registeredClasses = {};' . CRLF;
        foreach ($classes as $prefix => $group) {
            $bufferRegisteredClasses .= 'registeredClasses.' . $prefix . ' = {' . CRLF;
            $rows = [];
            foreach ($group as $sufix) {
                $rows[] = $sufix . ': ' . $prefix . '_' . $sufix;
            }
            $bufferRegisteredClasses .= implode(',' . CRLF, $rows);
            $bufferRegisteredClasses .= '};' . CRLF . CRLF;
        }


        $bufferStart = '';
        $location = PATH_APPLICATION . 'map.js';
        if (is_file($location)) {
            $bufferStart .= CRLF . file_get_contents($location) . CRLF;
        }

        $bufferStart .= file_get_contents(PATH_ENGINE . 'map.js');
        $bufferStart .= file_get_contents(PATH_ENGINE . 'start.js');

        if ($generateHTML) {
            return $bufferScripts . '<script>' . CRLF . $bufferStaticContents . $bufferTemplates . $bufferRegisteredClasses . $bufferStart . '</script>' . CRLF;
        } else {
            return $bufferScripts . $bufferStaticContents . $bufferTemplates . $bufferRegisteredClasses . $bufferStart;
        }
    }

    private static function concatList(array $items, string $prefix, string $sufix): string
    {
        $rows = [];
        foreach ($items as $name => $value) {
            $rows[] = $name . ': ' . $prefix . $value . $sufix;
        }
        return implode(',' . CRLF, $rows) . CRLF;
    }

}
