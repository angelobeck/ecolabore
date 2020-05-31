<?php

class eclFilter_adminDomains_details_adminSelect
{ // class eclFilter_adminDomains_details_adminSelect

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
$item->data['type'] = 'select';

$domainId = $me->domainId;
$group = $store->domainGroup->open ($domainId, 1);
foreach ($group as $userId => $status)
{ // each user
if (!$userId or $status != 4)
continue;

$data = $store->userContent->open ($userId, '-register');
if (isset ($data['text']['title']))
$caption = $data['text']['title'];
elseif (isset ($data['text']['caption']))
$caption = $data['text']['caption'];
else
$caption = $document->textMerge ('unknown');
$item->appendChild (false, array (
'value' => $userId, 
'caption' => $caption
));
} // each user
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
global $store;

if (!$formulary->command ('admin_remove'))
return false;

$userId = 0;
if (isset ($formulary->received[$fieldName][0]))
$userId = intval ($formulary->received[$fieldName]);

if (!$userId)
return $formulary->setErrorMsg ($control, $fieldName, 'adminDomains_details_alertAdminPleaseSelect');

$domainId = $formulary->document->application->domainId;
$group = $store->domainGroup->open ($domainId, 1);
if (!isset ($group[$userId]))
return $formulary->setErrorMsg ($control, $fieldName, 'adminDomains_details_alertAdminRemoveError');

$i = 0;
foreach ($group as $status)
{ // each status
if ($status == 4)
$i++;
} // each status

if ($i <= 1)
return $formulary->setErrorMsg ($control, $fieldName, 'adminDomains_details_alertAdminRemoveLast');

$formulary->data['admin_select'] = $userId;
} // function save

} // class eclFilter_adminDomains_details_adminSelect

?>