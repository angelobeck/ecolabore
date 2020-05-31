<?php

/*
* Valid control flags
* control_type = view
* control_filter = flag_view
* control_target
* control_field_name
* control_default_value
*/

class eclFilter_view
{ // class eclFilter_view

static function create ($fieldName, $control, $formulary)
{ // function create
$local['name'] = $fieldName;

// type
if (isset ($control['flags']['type']))
$local['type'] = $control['flags']['type'];
else
$local['type'] = 'view';

// target
if (isset ($control['flags']['target']))
$value = $formulary->getField ($control['flags']['target']);
else
$value = false;

if ($value !== false and $value !== '')
$local['content'] = $formulary->document->textMerge ($value);
elseif (isset ($control['flags']['default_value']))
$local['content'] = $document->textMerge ($control['flags']['default_value']);

$formulary->appendChild ($control, $local);
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
} // function save

} // class eclFilter_view

?>