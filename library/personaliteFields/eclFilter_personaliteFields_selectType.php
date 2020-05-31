<?php

class eclFilter_personaliteFields_selectType
{ // class eclFilter_personaliteFields_selectType

static function create ($fieldName, $control, $formulary)
{ // function create
global $store;
$document = $formulary->document;
$me = $document->application;

$control['name'] = $fieldName;
$control['type'] = 'select';

$item = $formulary->appendChild ('personaliteFields_editType', $control);

if (!isset ($control['flags']['search_filter']))
return;

$filter = $control['flags']['search_filter'];

$value = $formulary->getField ('type');

$userDetails = [];
foreach ($store->domainExtras->children ($me->domainId, MODE_TEMPLATE, 0) as $data)
{ // each user detail
if (substr ($data['name'], 0, 7) != 'fields/')
continue;

$userDetails[substr ($data['name'], 7)] = $data['id'];
} // each user detail

foreach ($store->control->scandir ('t', 'fields') as $name)
{ // each detail
if (isset ($userDetails[$name]))
{ // user detail
$data = $store->domainExtras->openById ($me->domainId, $userDetails[$name]);
unset ($userDetails[$name]);
} // user detail
else
$data = $store->control->read ('fields/' . $name);

if (!$data or !isset ($data['text']['caption']))
continue;

if (!isset ($data['local']['filters']))
continue;

foreach (explode (LF, $data['local']['filters']) as $line)
{ // each line
if ($filter != trim ($line))
continue;

$data['value'] = $name;
if ($name == $value)
$data['active'] = 1;
$item->appendChild ($data);
break;
} // each line
} // each detail

foreach ($userDetails as $name => $id)
{ // each user detail
$data = $store->domainExtras->openById ($me->domainId, $id);
$data['value'] = $name;

if (!isset ($data['local']['filters']))
continue;

foreach (explode (LF, $data['local']['filters']) as $line)
{ // each line
if ($filter != trim ($line))
continue;

$data['name'] = $name;
if ($name == $value)
$data['active'] = 1;

$item->appendChild ($data);
break;
} // each line
} // each user detail
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
if (isset ($formulary->received[$fieldName]))
$formulary->data['type'] = $formulary->received[$fieldName];
} // function save

} // class eclFilter_personaliteFields_selectType

?>