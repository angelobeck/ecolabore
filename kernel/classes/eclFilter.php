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
            'message' => 'The field ' . $control['target'] . ' was not checked properly. Choose a currect filter.'
        ];
    }

    static function view(eclEngine_formulary $formulary, array $control, string $name)
    {

    }

}