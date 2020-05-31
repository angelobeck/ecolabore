<?php

class eclFilter_admin_constants_selectOptions
{ // class eclFilter_admin_constants_selectOptions

static function create ($fieldName, $control, $formulary)
{ // function create
global $dataMap, $io, $store;
$document = $formulary->document;

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

if ($io->systemConstants->check ($target))
$value = $io->systemConstants->constants[$target];
elseif (defined ($target))
$value = constant ($target);
else
$value = false;

if (!isset ($control['options']))
return;

foreach ($control['options'] as $option)
{ // each option
$item->appendChild (false, array ('value' => $option, 'caption' => $document->textMerge ($option)))
->active ($option == $value);
} // each option
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
global $io, $store;

if (!isset ($control['flags']['target']))
return;

$target = $control['flags']['target'];

if (!isset ($formulary->received[$fieldName]))
return;

$value = $formulary->received[$fieldName];

$io->systemConstants->set ($target, $value);
} // function save

} // class eclFilter_admin_constants_selectOptions

?>