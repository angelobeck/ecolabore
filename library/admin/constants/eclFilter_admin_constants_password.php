<?php

/*
* control_type
* control_filter
* control_target
* control_field_name
*/

class eclFilter_admin_constants_password
{ // class eclFilter_admin_constants_password

static function create ($fieldName, $control, $formulary)
{ // function create
global $io, $store;

// target
if (!isset ($control['flags']['target']))
return;

// name
$local['name'] = $fieldName;

// type
if (isset ($control['flags']['type']))
$local['type'] = $control['flags']['type'];
else
$local['type'] = 'text_small';

$formulary->appendChild ($control, $local);

$control = $store->control->read ('labels/field/password2');
$local['name'] = $fieldName . '_2';
$formulary->appendChild ($control, $local);
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
global $io;

// target
if (!isset ($control['flags']['target']))
return;

$target = $control['flags']['target'];

if (isset ($formulary->received[$fieldName]))
$value = $formulary->received[$fieldName];
else
$value = false;

if ($value == '')
$value = false;

if (defined ('SYSTEM_INSTALLATION_PROGRESS') and $value === false and isset ($formulary->data[$target]))
return;

if ($value === false and isset ($control['flags']['required']))
return $formulary->setRequiredMsg ($control, $fieldName, 'system_msg_filterPasswordRequired');

if (!preg_match ('/^[\ -~]+$/', $value))
return $formulary->setErrorMsg ($control, $fieldName, 'system_msg_filterPasswordInvalidCharacter');

if (!isset ($formulary->received[$fieldName . '_2']))
return $formulary->setErrorMsg ($control, $fieldName, 'system_msg_filterPasswordRepeatError');

if ($formulary->received[$fieldName . '_2'] != $value)
return $formulary->setErrorMsg ($control, $fieldName, 'system_msg_filterPasswordRepeatError');

// target
if (defined ('SYSTEM_INSTALLATION_PROGRESS'))
$formulary->data[$target] = md5 ($value);
else
$io->systemConstants->set ($target, md5 ($value));
} // function save

} // class eclFilter_admin_constants_password

?>