<?php

class eclFilter_personaliteFields_separator
{ // class eclFilter_personaliteFields_separator

static function create ($fieldName, $control, $formulary)
{ // function create
// type
if (isset ($control['flags']['type']))
$control['type'] = $control['flags']['type'];
else
$control['type'] = 'separator';

$formulary->appendChild ($control);
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
} // function save

static function view ($fieldName, $control, $formulary)
{ // function view
$local['type'] = 'separator';
$formulary->appendChild ($local);
} // function view

static function column ($document, $data, $name, $field, $url)
{ // function column
return false;
} // function column

} // class eclFilter_personaliteFields_separator

?>