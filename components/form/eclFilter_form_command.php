<?php

class eclFilter_form_command extends eclFilter
{

    static function create(eclEngine_formulary $formulary, array $control, string $name)
    {
        $control['template'] = 'form_command';
        $control['name'] = $formulary->prefix . '_command_' . ($control['name'] ?? 'save');
        $formulary->appendChild($control);
            }

}
