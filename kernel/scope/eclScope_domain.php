<?php

class eclScope_domain extends eclScope
{
    public static function getScope(eclEngine_page $page, mixed $value, string $argument): mixed
    {
        return
            $page->domain->data['text'][$argument] ??
            $page->domain->data[$argument] ??
            '';
    }

}