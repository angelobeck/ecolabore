<?php

class eclScope_value extends eclScope
{

    public static function getScope(eclEngine_page $page, mixed $value, string $argument): mixed
    {
        $scope = $page->selectLanguage($value);
        return $scope['value'] ?? '';
    }

}

