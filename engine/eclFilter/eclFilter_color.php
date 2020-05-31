<?php

class eclFilter_color
{ // class eclFilter_color

static function create ($fieldName, $control, $formulary)
{ // function create
$local['url'] = $formulary->document->url (array ($formulary->document->domain->name, '-personalite', 'apearance', 'color'));

// name
$local['name'] = $fieldName;

// type
$local['type'] = 'color';

// help
if (isset ($control['flags']['help']) and !isset ($control['flags']['help_msg']))
$local['help_msg'] = 'system_msg_filterColorHelp';

// target
if (isset ($control['flags']['target']))
$local['value'] = $formulary->getField ($control['flags']['target']);

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

} // class eclFilter_color

?>