<?php

class eclApp
{

    static function isChild(eclEngine_application $parent, string $name): bool
    {
        return false;
    }

    static function childrenNames(eclEngine_application $parent): array
    {
        return [];
    }

    static function constructorHelper(eclEngine_application $me): void
    {

    }

    static function dispatch(eclEngine_page $page): void
    {

    }

    static function view_main(eclEngine_page $page): void
    {

    }

}
