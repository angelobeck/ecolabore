<?php

class eclFilter_sectionTeam_member_inactiveList
{ // class eclFilter_sectionTeam_member_inactiveList

static function create ($fieldName, $control, $formulary)
{ // function create
global $store;
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

$where = array (
'domain_id' => $me->domainId, 
'mode' => MODE_SUBSCRIPTION, 
'marker' => $me->parent->data['marker'], 
'access' => '> 200'
);
$profiles = $store->domainContent->search ($where, 255, 0, 0, 'name', 'asc');
foreach ($profiles as $data)
{ // each user
$item->appendChild ($data, array ('value' => $data['name']));
} // each user
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
global $store;
$me = $formulary->document->application;

if (!isset ($formulary->received[$fieldName]))
goto ERROR;

if (!strlen ($formulary->received[$fieldName]))
goto ERROR;

$name = $formulary->received[$fieldName];
$data = &$store->domainContent->open ($me->domainId, $name);
if ($data['mode'] != MODE_SUBSCRIPTION or $data['marker'] < 245)
goto ERROR;

$formulary->data = $data;
return;

ERROR:

$formulary->setErrorMsg ($control, $fieldName, 'sectionTeam_member_importListError');
} // function save

} // class eclFilter_sectionTeam_member_inactiveList

?>