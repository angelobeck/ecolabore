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
        if (SYSTEM_TIME_LIMIT !== 0) {
            set_time_limit(60);
        }

        $styles = file_get_contents(PATH_ROOT . 'reset.css');

        [$prefix, $applicationFolder] = explode('_', $page->domain->helper);
        $currentApplicationPath = PATH_APPLICATIONS . $applicationFolder;

        $paths = [
            [PATH_KERNEL, []],
            [PATH_LIBRARY, []],
            [$currentApplicationPath, [$applicationFolder]]
        ];
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

                if ($extension == 'css') {
                    $styles .= file_get_contents($nextPath) . CRLF;
                    continue;
                }
            }
        }

        if ($generateHTML) {
            return '<style>' . CRLF . $styles . CRLF . '</style>' . CRLF;
        }else{
        return $styles;
        }
    }

}