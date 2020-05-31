<?php

/*
* Valid control flags:
* control_type text_tiny_leftfield | text_tiny | text_small | range
* control_filter = integer
* control_target
* control_field_name
* control_clear
* control_default_value
* control_clear_default_value => clear the field when the value is the default value
* control_min_input => adjust the value if received is smaller
* control_max_input => adjust the value if received is bigger
* control_step => for use with range inputs
* control_help
* control_help_msg
*/

class eclFilter_integer
{ // class eclFilter_integer

static function create ($fieldName, $control, $formulary)
{ // function create
$local['name'] = $fieldName;

// type
if (isset ($control['flags']['type']))
$local['type'] = $control['flags']['type'];
else
$local['type'] = 'text_small';

// help
if (isset ($control['flags']['help']) and !isset ($control['flags']['help_msg']))
$local['help_msg'] = 'system_msg_filterIntegerHelp';

// target
if (isset ($control['flags']['target']))
$value = $formulary->getField ($control['flags']['target']);
else
$value = false;

// default_value
if ($value === false and isset ($control['flags']['default_value']))
$value = $control['flags']['default_value'];

$local['value'] = $value;

if (isset ($control['flags']['min_input']))
$local['min'] = $control['flags']['min_input'];
if (isset ($control['flags']['max_input']))
$local['max'] = $control['flags']['max_input'];
if (isset ($control['flags']['step']))
$local['step'] = $control['flags']['step'];

$formulary->appendChild ($control, $local);
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
if (isset ($formulary->received[$fieldName]))
{ // received value
$value = $formulary->received[$fieldName];
if (!preg_match ('/^[-]?[0-9]+$/', $value))
$value = false;
} // received value
else
$value = false;

// clear
if ($value === false and !isset ($control['flags']['clear']))
$value = 0;

if (isset ($control['flags']['default_value']) and isset ($control['flags']['clear_default_value']) and $value == $control['flags']['default_value'])
$value = false;

if ($value !== false)
{ // valid value

settype ($value, 'int');

// min_input
if (isset ($control['flags']['min_input']) and $value < $control['flags']['min_input'])
$value = $control['flags']['min_input'];

// max_input
if (isset ($control['flags']['max_input']) and $value > $control['flags']['max_input'])
$value = $control['flags']['max_input'];
} // received value

// target
if (isset ($control['flags']['target']))
$formulary->setField ($control['flags']['target'], $value);
} // function save

} // class eclFilter_integer

?>