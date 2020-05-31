<?php

class eclFilter_adminUsers_password
{ // class eclFilter_adminUsers_password

static function create ($fieldName, $control, $formulary)
{ // function create
$local['name'] = $fieldName;

// type
if (isset ($control['flags']['type']))
$local['type'] = $control['flags']['type'];
else
$local['type'] = 'text_password';

$formulary->appendChild ($control, $local);
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
isset ($formulary->received[$fieldName]) ? $password = strtolower ($formulary->received[$fieldName]) : $password = false;
isset ($formulary->received[$fieldName . '_repeat']) ? $password2 = strtolower ($formulary->received[$fieldName . '_repeat']) : $password2 = false;

if (!$password and $password !== '0')
$password = false;

// required
if ($password === false and isset ($control['flags']['required']))
return $formulary->setRequiredMsg ($control, $fieldName, 'adminUsers_alertPasswordRequired');

// no password, no required
if (!$password)
return;

if ($password != $password2)
return $formulary->setErrorMsg ($control, $fieldName, 'adminUsers_alertPasswordRepeatError');

foreach (str_split ($password) as $char)
{ // each char
if (ord ($char) < 32 or ord ($char) > 127)
return $formulary->setErrorMsg ($control, $fieldName, 'adminUsers_alertPasswordInvalidCharacter');
} // each char

// target
if (isset ($control['flags']['target']))
$formulary->setField ($control['flags']['target'], $password);
} // function save

} // class eclFilter_adminUsers_password

?>