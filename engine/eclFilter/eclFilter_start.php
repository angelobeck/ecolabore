<?php

class eclFilter_start
{ // class eclFilter_start

static function create ($fieldName, $control, $formulary)
{ // function create
$item = $formulary->appendChild ();
$item->data['type'] = 'start';

if (isset ($formulary->flags['field_name']))
$item->data['name'] = $formulary->flags['field_name'];

if (isset ($formulary->flags['action']))
$formulary->action = $formulary->flags['action'];

$item->data['url'] = $formulary->document->url ($formulary->pathway, $formulary->lang, $formulary->action, $formulary->protocol);
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
} // function save

} // class eclFilter_start

?>