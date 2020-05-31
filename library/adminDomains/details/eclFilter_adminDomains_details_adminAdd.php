<?php

class eclFilter_adminDomains_details_adminAdd
{ // class eclFilter_adminDomains_details_adminAdd

static function create ($fieldName, $control, $formulary)
{ // function create
$local['name'] = $fieldName;

// type
if (isset ($control['flags']['type']))
$local['type'] = $control['flags']['type'];
else
$local['type'] = 'text';

$formulary->appendChild ($control, $local);
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
global $store;

if (!$formulary->command ('admin_add'))
return;

if (isset ($formulary->received[$fieldName][0]))
{ // received value
$value = trim ($formulary->received[$fieldName], ' -_');
if (!preg_match ('/^[a-z0-9_-]+$/', $value))
$value = false;
} // received value
else
$value = false;

if ($value === false)
return $formulary->setRequiredMsg ($control, $fieldName);

$userId = $store->user->getId ($value);
if (!$userId)
$formulary->setErrorMsg ($control, $fieldName, 'adminDomains_details_alertAdminRequired');

$domainId = $formulary->document->application->domainId;

$group = $store->domainGroup->open ($domainId, 1);
if (isset ($group[$userId]) and $group[$userId] == 4)
$formulary->setErrorMsg ($control, $fieldName, 'adminDomains_details_alertAdminAlreadyExists', $value);

$formulary->setField ('admin_id', $userId);
$formulary->setField ('admin_name', $value);
} // function save

} // class eclFilter_adminDomains_details_adminAdd

?>