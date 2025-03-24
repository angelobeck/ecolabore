<?php

class eclScope_lang extends eclScope
{

    public static function getScope(eclEngine_page $page, mixed $value, string $argument): mixed
    {
        $scope = $page->selectLanguage($value);
        if ($scope['lang'] === $page->lang) {
            return '';
        }

        return $scope['lang'];
    }

}
