<?php

/*
* Control flags
* control_type
* control_target
* control_field_name
* control_required
*/

class eclFilter_adminDomains_add_name
{ // class eclFilter_adminDomains_add_name

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

// target
if (isset ($control['flags']['target']))
$item->data['value'] = $formulary->getField ($control['flags']['target']);

return $item;
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
if (!isset ($formulary->received[$fieldName]))
return $formulary->setErrorMsg ($control, $fieldName, 'adminDomains_add_editNameInvalidChars');

$value = trim ($formulary->received[$fieldName], ' -_');

if (!strlen ($value))
return $formulary->setErrorMsg ($control, $fieldName, 'adminDomains_add_editNameInvalidChars');

if (strlen ($value) > 18)
$formulary->setErrorMsg ($control, $fieldName, 'adminDomains_add_editNameSoLong');

elseif (!preg_match ('/^[a-z0-9][a-z0-9_-]+$/', $value))
$formulary->setErrorMsg ($control, $fieldName, 'adminDomains_add_editNameInvalidChars');

elseif (is_dir (PATH_DOMAINS . $value))
$formulary->setErrorMsg ($control, $fieldName, 'adminDomains_add_editNameAlreadyExists');

if (isset ($control['flags']['target']))
$formulary->setField ($control['flags']['target'], $value);
} // function save

} // class eclFilter_adminDomains_add_name

?>