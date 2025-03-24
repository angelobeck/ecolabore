<?php

class eclApp_systemJavascript extends eclApp
{
    public static function isChild(eclEngine_application $me, string $name): bool
    {
        if ($name === '-scripts' or $name === 'scripts')
            return true;
        else
            return false;
    }


    public static function childrenNames(eclEngine_application $me): array
    {
        return ['scripts'];
    }
    public static $map = ['systemJavascript_application'];
}
