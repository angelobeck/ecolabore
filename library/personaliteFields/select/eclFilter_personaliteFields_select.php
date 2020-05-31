<?php

class eclFilter_personaliteFields_select
{ // class eclFilter_personaliteFields_select

static function create ($fieldName, $control, $formulary)
{ // function create
$name = $control['flags']['field_name'];

// type
if (isset ($control['flags']['type']))
$control['type'] = $control['flags']['type'];
else
$control['type'] = 'select';

$control['name'] = $fieldName;
if (isset ($formulary->data['local'][$name]['value']))
$value = $formulary->data['local'][$name]['value'];
else
$value = false;

$select = $formulary->appendChild ($control);

if (isset ($control['local']['options']))
{ // options exists
foreach ($control['local']['options'] as $index => $caption)
{ // each option
$data = array (
'name' => $fieldName, 
'value' => strval ($index), 
'caption' => $caption
);
if ($index == $value)
$data['active'] = 1;

$select->appendChild ($data);
} // each option
} // options exists
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
$name = $control['flags']['field_name'];

if (isset ($formulary->received[$fieldName]))
$formulary->data['local'][$name]['value'] = $formulary->received[$fieldName];
} // function save

static function view ($fieldName, $control, $formulary)
{ // function view
$name = $control['flags']['field_name'];

$control['type'] = 'view';
if (isset ($formulary->data['local'][$name]['value']))
$value = $formulary->data['local'][$name]['value'];
else
$value = false;

if (isset ($control['local']['options']))
{ // options exists
foreach ($control['local']['options'] as $index => $caption)
{ // each option
if ($index != $value)
continue;

$control['content'] = $caption;
$formulary->appendChild ($control);
break;
} // each option
} // values exists
} // function view

static function column ($document, $data, $name, $field, $url)
{ // function column
if (isset ($data['local'][$name]))
$value = $data['local'][$name]['value'];
else
$value = false;

if (isset ($field['options']))
{ // options exists
foreach ($field['options'] as $index => $caption)
{ // each option
if ($index != $value)
continue;

return array ('caption' => $caption);
} // each option
} // values exists

return array ('caption' => $document->textMerge ('-'));
} // function column

} // class eclFilter_personaliteFields_select

?>