<?php

class eclFilter_personaliteFields_checkbox
{ // class eclFilter_personaliteFields_checkbox

static function create ($fieldName, $control, $formulary)
{ // function create
$name = $control['flags']['field_name'];
$local = $control;

// type
if (isset ($control['flags']['type']))
$control['type'] = $control['flags']['type'];
else
$control['type'] = 'checkbox';

$local['name'] = $fieldName;
if (isset ($formulary->data['local'][$name]['value']) and $formulary->data['local'][$name]['value'])
$local['value'] = 1;

$formulary->appendChild ($local);
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
$name = $control['flags']['field_name'];

if (isset ($formulary->received[$fieldName]))
$formulary->data['local'][$name]['value'] = 1;
else
$formulary->data['local'][$name]['value'] = 0;
} // function save

static function view ($fieldName, $control, $formulary)
{ // function view
global $store;
$name = $control['flags']['field_name'];

$control['type'] = 'view';

if (isset ($formulary->data['local'][$name]['value']) and $formulary->data['local'][$name]['value'])
$caption = $store->control->read ('personaliteFields_checkbox_valueYes');
else
$caption = $store->control->read ('personaliteFields_checkbox_valueNo');

$control['content'] = $caption['text']['caption'];

$formulary->appendChild ($control);
} // function view

static function column ($data, $name, $field, $url, $document)
{ // function column
if (isset ($data['local'][$name]['value']) and $data['local'][$name]['value'])
$caption = $store->control->read ('personaliteFields_checkbox_valueYes');
else
$caption = $store->control->read ('personaliteFields_checkbox_valueNo');

return $caption['text'];
} // function column

} // class eclFilter_personaliteFields_checkbox

?>