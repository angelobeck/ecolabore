<?php

class eclFilter_personaliteFields_descriptive
{ // class eclFilter_personaliteFields_descriptive

static function create ($fieldName, $control, $formulary)
{ // function create
// type
if (isset ($control['flags']['type']))
$control['type'] = $control['flags']['type'];
else
$control['type'] = 'descriptive';

$formulary->appendChild ($control);
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
} // function save

static function view ($fieldName, $control, $formulary)
{ // function view
if (isset ($control['display_when_editing']) and $control['display_when_editing'])
return;

$control['type'] = 'descriptive';
$formulary->appendChild ($control);
} // function view

static function column ($data, $name, $field, $url, $document)
{ // function column
return [];
} // function column

} // class eclFilter_personaliteFields_descriptive

?>