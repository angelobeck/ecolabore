<?php

class eclScope_user extends eclScope
{
    public static function getScope(eclEngine_page $page, mixed $value, string $argument): mixed
    {
        return
            $page->user->data['text'][$argument] ??
            $page->user->data[$argument] ??
            '';
    }

}
