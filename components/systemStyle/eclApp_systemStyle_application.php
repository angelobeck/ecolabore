<?php

class eclApp_systemStyle_application extends eclApp
{
    public static $name = 'application.css';

    public static function dispatch(eclEngine_page $page): void
    {
        $page->buffer = self::generate_style($page);
        $page->contentType = "text/css";
    }

    public static function generate_style(eclEngine_page $page, bool $generateHTML = false): string
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
                $paths[] = [PATH_ROOT . $plugin . '/components/', []];
            }
        }

        $subpaths = [];

        $currentPath = '';
        $nextPath = '';
        $folders = [];
        $styles = '/* Ecolabore Engine packager */' . CRLF;

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

                if ($extension == 'css') {
                    $styles .= CRLF . '/* ' . $className . ' */' . CRLF;
                    $styles .= trim(file_get_contents($nextPath) . CRLF) . CRLF;
                    continue;
                }
            }
        }

        if ($generateHTML) {
            return '<style>' . CRLF . $styles . CRLF . '</style>' . CRLF;
        } else {
            return $styles;
        }
    }

}