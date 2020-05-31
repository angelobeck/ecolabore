<?php

class eclFilter_adminDomains_add_admin
{ // class eclFilter_adminDomains_add_admin

static function create ($fieldName, $control, $formulary)
{ // function create
$item = $formulary->document->createListItem ($control);

// name
$item->data['name'] = $fieldName;

// type
if (isset ($control['flags']['type']))
$item->data['type'] = $control['flags']['type'];
else
$item->data['type'] = 'text';

return $item;
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
global $store;

if (!isset ($formulary->received[$fieldName]) or !preg_match ('/^[a-z0-9_-]+$/', $formulary->received[$fieldName]))
return $formulary->setRequiredMsg ($control, $fieldName, 'adminDomains_add_editAdminRequired');

$userId = $store->user->getId ($formulary->received[$fieldName]);

// required
if (!$userId)
return $formulary->setRequiredMsg ($control, $fieldName, 'adminDomains_add_editAdminRequired');

$formulary->data['admin_id'] = $userId;
} // function save

} // class eclFilter_adminDomains_add_admin

?>