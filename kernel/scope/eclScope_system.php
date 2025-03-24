<?php

class eclScope_system extends eclScope
{
    public static function getScope(eclEngine_page $page, mixed $value, string $argument): mixed
    {
        global $applications;
        return
            $applications->data['text'][$argument] ??
            $applications->data[$argument] ??
            '';
    }

}
