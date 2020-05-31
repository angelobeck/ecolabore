<?php

class eclFilter_section_selectGroup
{ // class eclFilter_section_selectGroup

static function create ($fieldName, $control, $formulary)
{ // function create
global $store;
$me = $formulary->document->application;

$groups = $store->domainContent->mode ($me->domainId, MODE_GROUP);

if (!$groups)
return;

$control['name'] = $fieldName;
$control['type'] = 'select';
$item = $formulary->appendChild ($control);
$value = $formulary->getField ('links/group');

$item->appendChild ('section_editGroupNone');

foreach ($groups as $data)
{ // each group
$local = array (
'caption' => $data['text']['title'], 
'value' => $data['id']
);
$item->appendChild ($local)
->active ($data['id'] == $value);
} // each group
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
global $store;
$me = $formulary->document->application;

if (isset ($formulary->received[$fieldName]))
$id = intval ($formulary->received[$fieldName]);
else
$id = false;

if ($id)
{ // find group
$data = $store->domainContent->openById ($me->domainId, $id);
if (!$data or $data['mode'] != MODE_GROUP)
$id = false;
} // find group

$formulary->setField ('links/group', $id);
} // function save

} // class eclFilter_section_selectGroup

?>