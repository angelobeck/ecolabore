<?php

class eclFilter_admin_constants_select
{ // class eclFilter_admin_constants_select

static function create ($fieldName, $control, $formulary)
{ // function create
global $io, $store;

if (!isset ($control['flags']['target']))
return;

$target = $control['flags']['target'];

$item = $formulary->appendChild ($control);

// name
$item->data['name'] = $fieldName;

// type
if (isset ($control['flags']['type']))
$item->data['type'] = $control['flags']['type'];
else
$item->data['type'] = 'select';

// target
if ($io->systemConstants->check ($target))
$value = $io->systemConstants->constants[$target];
elseif (defined ($target))
$value = constant ($target);
else
$value = false;

if (isset ($control['children']))
{ // children exists
foreach ($control['children'] as $child_name)
{ // each child
$data = $store->control->read ($child_name);
$child = $item->appendChild ($data);
if (isset ($data['flags']['value']))
{ // value exists
$child->data['value'] = $data['flags']['value'];
$child->active ($data['flags']['value'] == $value);
} // value exists
} // each child
} // children exists
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
global $io, $store;

if (isset ($formulary->received[$fieldName]))
$value = $formulary->received[$fieldName];
else
$value = false;

$found = false;

if (isset ($control['children']))
{ // children exists
foreach ($control['children'] as $child_name)
{ // each child
$child = $store->control->read ($child_name);
if (isset ($child['flags']['value']) and $child['flags']['value'] == $value)
{ // value found
$found = $value;
break;
} // value found
} // each child
} // children exists

$value = $found;

// required
if ($value === false and isset ($control['flags']['required']))
return $formulary->setRequiredMsg ($control, $fieldName);

// control_default_value
elseif ($value === false and isset ($control['flags']['default_value']))
$value = $control['flags']['default_value'];

// value cast
if (isset ($control['flags']['value_cast']))
{ // cast value
switch ($control['flags']['value_cast'])
{ // switch type
case 'int':
$value = intval ($value);
break;

case 'string':
$value = strval ($value);
break;

case 'bool':
if ($value === 'false' or !$value)
$value = false;
else
$value = true;
} // switch type
} // cast value

// target
if (isset ($control['flags']['target']))
$io->systemConstants->set ($control['flags']['target'], $value);
} // function save

} // class eclFilter_admin_constants_select

?>