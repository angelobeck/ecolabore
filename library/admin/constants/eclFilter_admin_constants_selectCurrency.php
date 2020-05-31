<?php

class eclFilter_admin_constants_selectCurrency
{ // class eclFilter_admin_constants_selectCurrency

static function create ($fieldName, $control, $formulary)
{ // function create
global $dataMap, $io, $store;
$document = $formulary->document;

if (!isset ($control['flags']['target']))
return;

$item = $formulary->appendChild ($control);

// name
$item->data['name'] = $fieldName;

// type
if (isset ($control['flags']['type']))
$item->data['type'] = $control['flags']['type'];
else
$item->data['type'] = 'select';

if ($io->systemConstants->check ($control['flags']['target']))
$value = $io->systemConstants->constants[$control['flags']['target']];
elseif (defined ($control['flags']['target']))
$value = constant ($control['flags']['target']);
else
$value = false;

$names = [];
if (isset ($dataMap['t']['labels/currency']))
{ // from embeded
$names = array_keys ($dataMap['t']['labels/currency']);
} // from embeded
elseif (is_dir (PATH_TEMPLATES . 'labels/currency'))
{ // from folder
foreach (scandir (PATH_TEMPLATES . 'labels/currency') as $filename)
{ // each file
if ($filename[0] == '.')
continue;

$names[] = substr ($filename, 0, 3);
} // each file
} // from folder

foreach ($names as $currency)
{ // each currency
$data = $store->control->read ('labels/currency/' . $currency);

$symbol = $currency . ' ';
if (isset ($data['local']['symbol']))
$symbol .= '(' . $data['local']['symbol'] . ') ';

$local = array ('value' => $currency);
if (isset ($data['text']))
$local['caption'] = $document->textMerge ($symbol, $data['text']['caption']);
else
$local['caption'] = $document->textMerge ($symbol, $data['text']['caption']);
if ($value == $currency)
$local['active'] = 1;

$item->appendChild ($local);
} // each lang
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
global $io, $store;

if (!isset ($formulary->received[$fieldName]))
return;

$value = $formulary->received[$fieldName];

// target
if (isset ($control['flags']['target']))
$io->systemConstants->set ($control['flags']['target'], $value);
} // function save

} // class eclFilter_admin_constants_selectCurrency

?>