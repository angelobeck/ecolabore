<?php

class eclApp_userProfile extends eclApp
{

    static function isChild(eclEngine_application $parent, string $name): bool
    {
        global $store;
        return !!$store->user->open($name);
    }

    static function childrenNames(eclEngine_application $parent): array
    {
        return [];
    }

    static function constructorHelper(eclEngine_application $me): void
    {
global $store;
$user = $store->user->open($me->name);
$me->userId = $user['id'];
$me->data = &$store->userContent->open($me->userId, '-index');
    }

    static function dispatch(eclEngine_page $page): void
    {

    }

    static function view_main(eclEngine_page $page): void
    {

    }

}
