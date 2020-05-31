<?php

/*
* Valid control flags
* control_type = text_small | text | text_tiny | textarea_small | textarea | textarea_big | textarea_full
* control_filter = free
* control_target
* control_field_name
* control_default_value
* control_help
* control_help_msg
* control_error
* control_error_msg
* control_required
* control_required_msg
* control_clear
*/

class eclFilter_free
{ // class eclFilter_free

static function create ($fieldName, $control, $formulary)
{ // function create
$local['name'] = $fieldName;

// type
if (isset ($control['flags']['type']))
$local['type'] = $control['flags']['type'];
else
$local['type'] = 'textarea';

// help
if (isset ($control['flags']['help']) and !isset ($control['flags']['help_msg']))
$local['help_msg'] = 'system_filterFreeHelp';

// target
if (isset ($control['flags']['target']))
$value = $formulary->htmlSpecialChars ($formulary->getField ($control['flags']['target']));
else
$value = false;

// default value
if ( ($value === false or $value === '') and isset ($control['flags']['default_value']))
$value = $control['flags']['default_value'];

$local['value'] = $value;

$formulary->appendChild ($control, $local);
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
if (isset ($formulary->received[$fieldName]) and strlen ($formulary->received[$fieldName]))
$value = $formulary->received[$fieldName];
else
$value = false;

// required
if ($value === false and isset ($control['flags']['required']))
return $formulary->setRequiredMsg ($control, $fieldName);

// clear
if ($value === false and !isset ($control['flags']['clear']))
$value = '';

// target
if (isset ($control['flags']['target']))
$formulary->setField ($control['flags']['target'], $value);
} // function save

} // class eclFilter_free

?>