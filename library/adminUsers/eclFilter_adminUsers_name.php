<?php

class eclFilter_adminUsers_name
{ // class eclFilter_adminUsers_name

static function create ($fieldName, $control, $formulary)
{ // function create

$formulary->appendChild ($control, [
'type' => 'identifier',
'name' => $fieldName,
'nick' => $formulary->getField ('text/caption'),
'identifier' => $formulary->getField ('name')
]);
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
global $store;
$document = $formulary->document;
$received = $formulary->received;

if (!isset ($received[$fieldName][0]))
return $formulary->setRequiredMsg ($control, $fieldName);

$formulary->data['text'] = ['caption' => [$document->lang => [TEXT_CONTENT => $received[$fieldName]]]];
if ($document->charset == 'ISO-8859-1')
$formulary->data['text']['caption'][$document->lang][TEXT_CHARSET] = 1; 

if (!isset ($received[$fieldName . '_identifier'][0]))
return $formulary->setRequiredMsg (array_replace ($control, $store->control->read ('labels/field/user_identifier')), $fieldName . '_identifier');

$identifier = trim ($received[$fieldName . '_identifier'], ' -_');
$formulary->data['name'] = $identifier;

if (!preg_match ('/^[a-z0-9_-]+$/', $identifier))
return $formulary->setErrorMsg ($control, $fieldName . '_identifier', 'adminUsers_alertUserInvalidName', $identifier);

if ($store->user->getId ($identifier))
return $formulary->setErrorMsg ($control, $fieldName . '_identifier', 'adminUsers_alertUserAlreadyExists', $identifier);
} // function save

} // class eclFilter_adminUsers_name

?>