<?php

class eclApp_systemLogin extends eclApp{
    public static $name = '-login';
    public static $content = 'systemLogin_main';

    public static function constructorHelper(eclEngine_application $me): void
    {
        $me->ignoreSubfolders = true;
    }

    public static function view_main(eclEngine_page $page): void
    {
        $page->modules->layout = 'systemLogin_main';
    }
}
