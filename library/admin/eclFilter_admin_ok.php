<?php

class eclFilter_admin_ok
{ // class eclFilter_admin_ok

static function create ($fieldName, $control, $formulary)
{ // function create
$local['name'] = $fieldName;

// type
if (isset ($control['flags']['type']))
$local['type'] = $control['flags']['type'];
else
$local['type'] = 'text_tiny';

$formulary->appendChild ($control, $local);
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
if (!isset ($formulary->received[$fieldName]) or !preg_match ('/^["]?[oO][kK]["]?$/', $formulary->received[$fieldName]))
$formulary->setErrorMsg ($control, $fieldName, 'system_msg_filterConfirmWithOkError');
} // function save

} // class eclFilter_admin_ok

?>