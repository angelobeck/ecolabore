<?php

class eclFilter_sectionTeam_member_selectGroup
{ // class eclFilter_sectionTeam_member_selectGroup

static function create ($fieldName, $control, $formulary)
{ // function create
global $io, $store;
$document = $formulary->document;
$me = $document->application;
$marker = $me->parent->data['marker'];
$item = $formulary->appendChild ($control);

// name
$item->data['name'] = $fieldName;

// type
if (isset ($control['flags']['type']))
$item->data['type'] = $control['flags']['type'];
else
$item->data['type'] = 'select';

$item->appendChild ('sectionTeam_member_editGroupNone');

// value
$id = $formulary->getField ('parent_id');

$groups = $store->domainContent->children ($me->domainId, MODE_GROUP, 0);
foreach ($groups as $data)
{ // each user
if ($data['marker'] != $marker)
continue;

$item->appendChild (false, array (
'value' => $data['id'], 
'caption' => $data['text']['caption']
))
->active ($data['id'] == $id);
} // each user
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
global $store;
$me = $formulary->document->application;

if (!isset ($formulary->received[$fieldName][0]))
return $formulary->data['parent_id'] = 0;

$id = intval ($formulary->received[$fieldName]);
if (!$id)
return $formulary->data['parent_id'] = 0;

$data = $store->domainContent->openById ($me->domainId, $id);
if (!$data or $data['mode'] != MODE_GROUP)
return $formulary->data['parent_id'] = 0;

$formulary->data['parent_id'] = $id;
} // function save

} // class eclFilter_sectionTeam_member_selectGroup

?>