<?php

class eclScope_label extends eclScope
{
    public static function getScope(eclEngine_page $page, mixed $value, string $argument): mixed
    {
        global $store;
        if (!$argument) {
            return [];
        }

        $data = $store->staticContent->open($argument);
        if (isset($data['text']['label'])) {
            return $data['text']['label'];
        }
        if (isset($data['text']['title'])) {
            return $data['text']['title'];
        }

        return [];
    }

}