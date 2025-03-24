<?php

class eclApp_systemNotFound extends eclApp{
    public static $name = '-not-found';
    public static $content = 'systemNotFound_main';

    public static function constructorHelper(eclEngine_application $me): void
    {
        $me->ignoreSubfolders = true;
    }

}
