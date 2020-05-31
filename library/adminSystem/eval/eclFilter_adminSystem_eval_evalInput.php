<?php

class eclFilter_adminSystem_eval_evalInput
{ // class eclFilter_adminSystem_eval_evalInput

static function create ($fieldName, $control, $formulary)
{ // function create
global $io, $store, $system, $groups, $document;

$item = $formulary->document->createListItem ($control);

// name
$item->data['name'] = $fieldName;

// type
if (isset ($control['flags']['type']))
$item->data['type'] = $control['flags']['type'];
else
$item->data['type'] = 'textarea';

// eval
if (isset ($formulary->received[$fieldName]) and $formulary->command ('save'))
{ // instructions received
eval ($formulary->received[$fieldName]);
$item->data['value'] = $formulary->htmlSpecialChars ($formulary->received[$fieldName]);
} // instructions received

return $item;
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
} // function save

} // class eclFilter_adminSystem_eval_evalInput

?>