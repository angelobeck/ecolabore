<?php

class eclFilter_userRecoverPassword_identifier
{ // class eclFilter_userRecoverPassword_identifier

static function create ($fieldName, $control, $formulary)
{ // function create
$control['name'] = $fieldName;

// type
if (isset ($control['flags']['type']))
$control['type'] = $control['flags']['type'];
else
$control['type'] = 'text';

$formulary->appendChild ($control);
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
global $store;

if (!isset ($formulary->received[$fieldName]) or !preg_match ('/^[a-z0-9_-]+$/', $formulary->received[$fieldName]))
return $formulary->setRequiredMsg ($control, $fieldName, 'userRecoverPassword_alertInvalidUser');

$userId = $store->user->getId ($formulary->received[$fieldName]);

// required
if (!$userId)
return $formulary->setRequiredMsg ($control, $fieldName, 'userRecoverPassword_alertInvalidUser');

$data = $store->userContent->open ($userId, '-register');

if (!$data or !isset ($data['local']['mail']) or isset ($data['flags']['userRecoverPassword_block']))
return $formulary->setRequiredMsg ($control, $fieldName, 'userRecoverPassword_alertInvalidUser');

$formulary->data = $data;
$formulary->data['identifier'] = $formulary->received[$fieldName];
} // function save

} // class eclFilter_userRecoverPassword_identifier

?>