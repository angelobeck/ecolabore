<?php

class eclFilter_sectionTeam_member_importList
{ // class eclFilter_sectionTeam_member_importList

static function create ($fieldName, $control, $formulary)
{ // function create
global $io, $store;
$document = $formulary->document;
$me = $document->application;
$item = $formulary->appendChild ($control);

// name
$item->data['name'] = $fieldName;

// type
if (isset ($control['flags']['type']))
$item->data['type'] = $control['flags']['type'];
else
$item->data['type'] = 'list';
$item->data['size'] = 15;

$users = $store->domainGroup->getDomainSubscribedUsers ($me->domainId);
foreach ($store->user->childrenNames () as $name)
{ // each user
$userId = $store->user->getId ($name);

if (isset ($users[$userId]))
continue;

$data = $store->userContent->open ($userId, '-register');
if (isset ($data['text']['title']))
$caption = $data['text']['title'];
elseif (isset ($data['text']['caption']))
$caption = $data['text']['caption'];
else
$caption = $document->textMerge ('unknown');
$item->appendChild (false, array (
'value' => $name, 
'caption' => $caption
));
} // each user
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
global $store;

if (!isset ($formulary->received[$fieldName]))
goto ERROR;

if (!strlen ($formulary->received[$fieldName]))
goto ERROR;

$name = $formulary->received[$fieldName];
$userId = $store->user->getId ($name);
if (!$userId)
goto ERROR;

$formulary->data = $store->userContent->open ($userId, '-register');
$formulary->data['name'] = $name;
return;

ERROR:

$formulary->setErrorMsg ($control, $fieldName, 'sectionTeam_member_importListError');
} // function save

} // class eclFilter_sectionTeam_member_importList

?>