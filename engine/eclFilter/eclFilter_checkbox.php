<?php

/*
* Valid control flags
* control_type = checkbox
* control_filter = checkbox
* control_target
* control_field_name
* control_invert => invert selection before showing and before saving
* control_clear => if value == 0, clears the target flag
* control_true_value => specifies a value to save when checked, like 'yes' - note that its depends of control_invert
* control_false_value => specifies a value to save when not checked, like 'no' - note that its depends of control_invert
* control_help
* control_help_msg
*/

class eclFilter_checkbox
{ // class eclFilter_checkbox

static function create ($fieldName, $control, $formulary)
{ // function create
$local['name'] = $fieldName;

// type
if (isset ($control['flags']['type']))
$local['type'] = $control['flags']['type'];
else
$local['type'] = 'checkbox';

// help
if (isset ($control['flags']['help']) and !isset ($control['flags']['help_msg']))
$local['help_msg'] = 'system_filterCheckboxHelp';

// value
$value = 0;
if (isset ($control['flags']['target']))
$value = $formulary->getField ($control['flags']['target']);

if (isset ($control['flags']['true_value']))
{ // predefined value
if ($value == $control['flags']['true_value'])
$value = 1;
else
$value = 0;
} // predefined value
elseif (isset ($control['flags']['false_value']))
{ // predefined false value
if ($value == $control['flags']['false_value'])
$value = 0;
else
$value = 1;
} // predefined false value
elseif ($value)
$value = 1;
else
$value = 0;

// invert
if (isset ($control['flags']['invert']))
$value ? $value = 0 : $value = 1;

if ($value)
$local['value'] = 1;

$formulary->appendChild ($control, $local);
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
if (isset ($formulary->received[$fieldName]) and $formulary->received[$fieldName])
$value = 1;
else
$value = 0;

// invert
if (isset ($control['flags']['invert']))
$value ? $value = 0 : $value = 1;

// clear
if (isset ($control['flags']['clear']) and !$value)
$value = false;

// true value
if ($value and isset ($control['flags']['true_value']))
$value = $control['flags']['true_value'];

// false value
elseif (!$value and isset ($control['flags']['false_value']))
$value = $control['flags']['false_value'];

// target
if (isset ($control['flags']['target']))
$formulary->setField ($control['flags']['target'], $value);
} // function save

} // class eclFilter_checkbox

?>