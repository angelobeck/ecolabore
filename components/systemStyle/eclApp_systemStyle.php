<?php

class eclApp_systemStyle extends eclApp
{
    public static function isChild(eclEngine_application $me, string $name): bool
    {
        if ($name === '-styles' or $name === 'styles')
            return true;
        else
            return false;
    }

    public static function childrenNames(eclEngine_application $me): array
    {
        return ['styles'];
    }
    public static $map = ['systemStyle_application'];
}
