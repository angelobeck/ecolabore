<?php

class eclScope_application extends eclScope
{
    public static function getScope(eclEngine_page $page, mixed $value, string $argument): mixed
    {
        return
            $page->application->data['text'][$argument] ??
            $page->application->data[$argument] ??
            '';
    }

}