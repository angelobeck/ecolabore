<?php

/*
* Valid control flags
* control_type = text_small | text | text_tiny | textarea_small | textarea | textarea_big | textarea_full
* control_filter = regexp
* control_target
* control_field_name
* control_regexp => an regular expression like /[a-z][a-z0-9_-]+
* control_default_value
* control_help
* control_help_msg
* control_error
* control_error_msg
* control_required
* control_required_msg
* control_clear
*/

class eclFilter_regexp
{ // class eclFilter_regexp

static function create ($fieldName, $control, $formulary)
{ // function create
$local['name'] = $fieldName;

// type
if (isset ($control['flags']['type']))
$local['type'] = $control['flags']['type'];
else
$local['type'] = 'text';

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

// the regular expression
if ($value !== false and isset ($control['flags']['regexp']) and !preg_match ($control['flags']['regexp'], $value))
return $formulary->setErrorMsg ($control, $fieldName);

// clear
if ($value === false and !isset ($control['flags']['clear']))
$value = '';

// target
if (isset ($control['flags']['target']))
$formulary->setField ($control['flags']['target'], $value);
} // function save

} // class eclFilter_regexp

?>