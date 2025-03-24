<?php

class eclFilter_form_link extends eclFilter
{

    static function create(eclEngine_formulary $formulary, array $control, string $name)
    {
        $control['template'] = 'form_link';
        $control['url'] = $formulary->page->url(true, true, $control['action']);
        $formulary->appendChild($control);
            }

}
