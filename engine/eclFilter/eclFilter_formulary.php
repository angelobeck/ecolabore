<?php

/*
* Valid control flags
* control_filter = formulary
* control_base_target = A prefix for each control_target, like 'path/'
* control_base_name = a prefix for each field name, like 'path'
* 
* Note that the control needs children
*/

class eclFilter_formulary
{ // class eclFilter_formulary

static function create ($fieldName, $control, $formulary)
{ // function create
if (!isset ($control['children']))
return;

// name
$local['name'] = $fieldName;

// type
if (isset ($control['flags']['type']))
$local['type'] = $control['flags']['type'];
else
$local['type'] = 'fields';

$formulary->appendChild ($control, $local);
$formulary->levelUp ($control);
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
$formulary->insertControlChildren ($control);
} // function save

} // class eclFilter_formulary

?>