<?php

/*
* Control flags
* control_type
* control_filter = password
* control_target
* control_field_name
* control_required
* control_error_msg
*/

class eclFilter_systemInstallation_password
{ // class eclFilter_systemInstallation_password

static function create ($fieldName, $control, $formulary)
{ // function create
$local['name'] = $fieldName;

// type
if (isset ($control['flags']['type']))
$local['type'] = $control['flags']['type'];
else
$local['type'] = 'text_password';

$formulary->appendChild ($control, $local);

$local['name'] .= '_repeat';
$formulary->appendChild ('labels/field/user_repeat_password', $local);
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
isset ($formulary->received[$fieldName]) ? $password = $formulary->received[$fieldName] : $password = false;
isset ($formulary->received[$fieldName . '_repeat']) ? $password2 = $formulary->received[$fieldName . '_repeat'] : $password2 = false;

if (!$password and $password !== '0')
$password = false;

// required
if ($password === false and isset ($control['flags']['required']))
return $formulary->setRequiredMsg ($control, $fieldName, 'adminUsers_add_msgPasswordRequired');

// no password, no required
if (!$password)
return;

if ($password != $password2)
return $formulary->setErrorMsg ($control, $fieldName, 'adminUsers_add_msgPasswordRepeatError');

foreach (str_split ($password) as $char)
{ // each char
if (ord ($char) < 32 or ord ($char) > 127)
return $formulary->setErrorMsg ($control, $fieldName, 'adminUsers_add_msgPasswordInvalidCharacter');
} // each char

// md5
if (isset ($control['flags']['password_md5']))
$password = eclIo_database::password($password);

// target
if (isset ($control['flags']['target']))
$formulary->setField ($control['flags']['target'], $password);
} // function save

} // class eclFilter_systemInstallation_password

?>