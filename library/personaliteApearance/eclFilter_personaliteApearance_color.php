<?php

class eclFilter_personaliteApearance_color
{ // class eclFilter_personaliteApearance_color

static function create ($fieldName, $control, $formulary)
{ // function create
$local['url'] = $formulary->document->url (array ($formulary->document->domain->name, '-personalite', 'apearance', 'color'));

// name
$local['name'] = $fieldName;

// type
$local['type'] = 'color_testable';

// help
if (isset ($control['flags']['help']) and !isset ($control['flags']['help_msg']))
$local['help_msg'] = 'system_msg_filterColorHelp';

// target
if (isset ($control['flags']['target']))
$local['value'] = $formulary->getField ($control['flags']['target']);

// default
if (isset ($control['flags']['default']))
{ // default
$default = $formulary->getField ($control['flags']['default']);
if ($default[0] == '$')
$local['from'] = str_replace ('-', '_', substr ($default, 1));
else
$local['default'] = $default;
} // default

// Current
if (isset ($local['value']))
$local['current'] = $local['value'];
elseif (isset ($local['default']))
$local['current'] = $local['default'];

// property
if (isset ($control['flags']['property']))
$local['property'] = $control['flags']['property'];

// class
if (isset ($control['flags']['field_name']))
{ // class
$local['class'] = $control['flags']['field_name'];
$local['target'] = str_replace ('-', '_', $control['flags']['field_name']);
} // class

$formulary->appendChild ($control, $local);
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
if (isset ($formulary->received[$fieldName]) and strlen ($formulary->received[$fieldName]))
{ // received value
$value = $formulary->received[$fieldName];
if (!preg_match ('/^[a-zA-Z0-9\ #(),.]+$/', $value))
{ // error

// error
if (isset ($control['flags']['error']))
$formulary->setErrorMsg ($control, $fieldName, 'system_msg_filterColorError', $value);
else
$value = false;
} // error
} // received value
else
$value = false;

// required
if ($value === false and isset ($control['flags']['required']))
return $formulary->setRequiredMsg ($control, $fieldName);

// target
if (isset ($control['flags']['target']))
$formulary->setField ($control['flags']['target'], $value);
} // function save

} // class eclFilter_personaliteApearance_color

?>