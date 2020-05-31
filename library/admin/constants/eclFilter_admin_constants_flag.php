<?php

/*
* control_type
* control_filter
* control_target
* control_field_name
* control_value_cast int | string | bool
*/

class eclFilter_admin_constants_flag
{ // class eclFilter_admin_constants_flag

static function create ($fieldName, $control, $formulary)
{ // function create
global $io, $store;

// target
if (!isset ($control['flags']['target']))
return;

$target = $control['flags']['target'];

// name
$local['name'] = $fieldName;

// type
if (isset ($control['flags']['type']))
$local['type'] = $control['flags']['type'];
else
$local['type'] = 'text_small';

// target
$value = '';

if (defined ('SYSTEM_INSTALLATION_PROGRESS') and isset ($formulary->data[$target]))
$value = $formulary->data[$target];
else
{ // from constant
if ($io->systemConstants->check ($target))
$value = $io->systemConstants->constants[$target];
elseif (defined ($target))
$value = constant ($target);
} // from constant

if ($formulary->document->charset != 'UTF-8' and is_string ($value))
$value = mb_convert_encoding ($value, $formulary->document->charset, 'UTF-8');

if (is_string ($value))
$value = $formulary->htmlSpecialChars ($value);

if (!isset ($control['children']))
{ // no children
$local['value'] = $value;
$formulary->appendChild ($control, $local);
return;
} // no children

$item = $formulary->appendChild ($control, $local);

foreach ($control['children'] as $child)
{ // each child
$data = $store->control->read ($child);
if (!isset ($data['flags']['value']) or !isset ($data['text']['caption']))
continue;

$local = array (
'value' => $data['flags']['value'], 
'caption' => $data['text']['caption']
);
$item->appendChild (false, $local)
->active ($data['flags']['value'] == $value);
} // each child
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
global $io;

// target
if (!isset ($control['flags']['target']))
return;

$target = $control['flags']['target'];

if (isset ($formulary->received[$fieldName]))
$value = $formulary->received[$fieldName];
else
$value = false;

if ($value === false and isset ($control['flags']['required']))
$formulary->setRequiredMsg ($control, $fieldName);

if (isset ($control['flags']['value_cast']))
{ // cast value
switch ($control['flags']['value_cast'])
{ // switch type
case 'int':
$value = intval ($value);
break;

case 'string':
$value = strval ($value);
if (!preg_match ('/^[\ -~]*$/', $value))
return $formulary->setErrorMsg ($control, $fieldName, 'system_msg_filterFlagError', $value);
break;

case 'bool':
if ($value === 'false' or !$value)
$value = false;
else
$value = true;
break;

case 'free':
$value = $formulary->received[$fieldName];
if ($formulary->document->charset != 'UTF-8')
$value = mb_convert_encoding ($value, 'UTF-8', $formulary->document->charset);
} // switch type
} // cast value

// target
if (defined ('SYSTEM_INSTALLATION_PROGRESS'))
$formulary->data[$target] = $value;
else
$io->systemConstants->set ($target, $value);
} // function save

} // class eclFilter_admin_constants_flag

?>