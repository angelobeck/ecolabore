<?php

class eclFilter_admin_constants_selectLang
{ // class eclFilter_admin_constants_selectLang

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
if (isset ($dataMap['t']['labels/lang']))
{ // from embeded
$names = array_keys ($dataMap['t']['labels/lang']);
} // from embeded
elseif (is_dir (PATH_TEMPLATES . 'labels/lang'))
{ // from folder
foreach (scandir (PATH_TEMPLATES . 'labels/lang') as $filename)
{ // each file
if ($filename[0] == '.')
continue;

$names[] = substr ($filename, 0, 2);
} // each file
} // from folder

foreach ($names as $lang)
{ // each lang
$data = $store->control->read ('labels/lang/' . $lang);
$item->appendChild ($data, array ('value' => $lang))
->active ($lang == $value);
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

} // class eclFilter_admin_constants_selectLang

?>