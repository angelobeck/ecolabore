<?php

/*
* control_type = select_testable
* control_filter = selectTestable
* control_field_name
* control_target
* control_default
* control_help_msg
* 
* This filter aways clears empty fields
*/

class eclFilter_personaliteApearance_select
{ // class eclFilter_personaliteApearance_select

static function create ($fieldName, $control, $formulary)
{ // function create
global $store;
$local['url'] = $formulary->document->url (array ($formulary->document->domain->name, '-dialog', 'color'));

// name
$local['name'] = $fieldName;

// type
$local['type'] = 'select_testable';

// target
if (isset ($control['flags']['target']))
$value = $formulary->getField ($control['flags']['target']);
else
$value = false;

// default
if (isset ($control['flags']['default']))
$default = $formulary->getField ($control['flags']['default']);
else
$default = false;

if (is_string ($default))
{ // set default
if ($default[0] == '$')
{ // default from target
$local['from'] = str_replace ('-', '_', substr ($default, 1));
$default = false;
} // default from target
else
$local['default'] = $default;
} // set default

if ($value === false or $value === '')
$value = $default;
if ($value === false)
$value = '';

// property
if (isset ($control['flags']['property']))
$local['property'] = $control['flags']['property'];

// class
if (isset ($control['flags']['field_name']))
{ // class
$local['class'] = $control['flags']['field_name'];
$local['target'] = str_replace ('-', '_', $control['flags']['field_name']);
} // class

$select = $formulary->appendChild ($control, $local);
if (!isset ($control['children']))
return;
foreach ($control['children'] as $childName)
{ // each child
$child = $store->control->read ($childName);
if (!$child)
continue;

$currentValue = $child['flags']['value'];

if ($currentValue === $value)
$active = 'true';
else
$active = '';

$select->appendChild ($child, array (
'value' => $currentValue, 
'active' => $active
));
} // each child
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
$default = false;
if (isset ($control['flags']['default']))
$default = $formulary->getField ($control['flags']['default']);
if (is_string ($default) and $default[0] == '$')
$default = false;

if (isset ($formulary->received[$fieldName]) and strlen ($formulary->received[$fieldName]))
{ // received
$value = $formulary->received[$fieldName];
if (!preg_match ('/^[a-zA-Z0-9\ #(),.-]+$/', $value))
$value = false;
} // received value
else
$value = false;

if ($value === $default)
$value = false;

// target
if (isset ($control['flags']['target']))
$formulary->setField ($control['flags']['target'], $value);
} // function save

} // class eclFilter_personaliteApearance_select

?>