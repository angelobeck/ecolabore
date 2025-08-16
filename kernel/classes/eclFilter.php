<?php

class eclFilter
{
    static function create(eclEngine_formulary $formulary, array $control, string $name)
    {

    }

    static function save(eclEngine_formulary $formulary, array $control, string $name)
    {

    }

    static function sanitize(eclEngine_formulary $formulary, array $control): array
    {
        return [
            'message' => 'filter_sanitizeFilterNotFound',
            'details' => $control
        ];
    }

    static function view(eclEngine_formulary $formulary, array $control, string $name)
    {

    }

}