<?php

class eclApp_root extends eclApp
{

    static function constructorHelper(eclEngine_application $me): void
    {
        $me->map = [...getMap('root'), 'systemUsers', 'systemJavascript', 'systemNotFound'];
    }

}
